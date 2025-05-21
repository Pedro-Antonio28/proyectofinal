<?php

namespace App\Http\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination;

    public $search = '';
    public $showTrashed = false;

    protected $queryString = ['search', 'showTrashed'];

    protected $listeners = ['postDeleted' => '$refresh'];

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

    public function render()
    {
        $query = Post::query()
            ->where('user_id', auth()->id())
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%' . $this->search . '%'));

        if ($this->showTrashed) {
            $query->onlyTrashed();
        }

        return view('livewire.blog.post-list', [
            'posts' => $query->latest()->paginate(10),
        ]);
    }
}
