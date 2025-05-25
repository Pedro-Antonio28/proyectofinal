<?php

namespace App\Http\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Like;
use Livewire\Attributes\On;

class PostList extends Component
{
    use WithPagination;

    public $search = '';
    public $showTrashed = false;

    public array $likesCount = [];

    protected $queryString = ['search', 'showTrashed'];
    protected $listeners = [
        'likePost' => 'toggleLike',
        'restorePost' => 'restore',
        'deletePost' => 'delete',
    ];


    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function delete(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        $this->emit('postDeleted');
    }

    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $this->authorize('delete', $post);
        $post->restore();
        $this->emit('postDeleted');
    }

    #[On('likePost')]
    public function toggleLike($postId)
    {
        $user = auth()->user();
        $post = Post::findOrFail($postId);

        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
        } else {
            $post->likes()->create(['user_id' => $user->id]);
        }

        // 🔄 Actualizar contador solo del post afectado
        $this->likesCount[$postId] = $post->likes()->count();
    }

    public function render()
    {
        $query = Post::withCount('likes')
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'));

        if ($this->showTrashed) {
            $query->onlyTrashed();
        }

        $posts = $query->latest()->paginate(10);

        // Inicializar contador de likes
        foreach ($posts as $post) {
            $this->likesCount[$post->id] = $post->likes_count;
        }

        return view('livewire.blog.post-list', [
            'posts' => $posts,
        ])->layout('layouts.livewireLayout');
    }
}
