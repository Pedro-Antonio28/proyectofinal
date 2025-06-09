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

    protected $queryString = ['search', 'showTrashed', 'ingrediente', 'mostrarFavoritos'];

    protected $listeners = [
        'likePost' => 'toggleLike',
        'restorePost' => 'restore',
        'deletePost' => 'delete',
    ];

    public string $ingrediente = '';
    public string $ingredienteABuscar = '';


    public bool $mostrarFavoritos = false;


    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function delete($postId)
    {
        $post = \App\Models\Post::findOrFail($postId);
        $this->authorize('delete', $post);
        $post->delete();
        $this->dispatch('postDeleted');
        return redirect()->route('posts.index');

    }

    public function buscarPorIngrediente()
    {
        $this->resetPage();
        $this->ingredienteABuscar = $this->ingrediente;
    }


    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $this->authorize('delete', $post);
        $post->restore();
        $this->dispatch('postRestaurado');
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

    public function updatedMostrarFavoritos()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Post::withCount('likes')
            ->when($this->search, fn($q) =>
            $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->when($this->ingredienteABuscar !== '', fn($q) =>
            $q->conIngredientesSimilares($this->ingredienteABuscar)
            )
            ->when($this->mostrarFavoritos && auth()->check(), function ($q) {
                $q->whereHas('usersWhoSavedIt', function ($query) {
                    $query->where('user_id', auth()->id())
                        ->where('es_favorito', true);
                });
            });

        if ($this->showTrashed) {
            $query->onlyTrashed();
        }

        $posts = $query->paginate(10);

        // Contador de likes
        foreach ($posts as $post) {
            $this->likesCount[$post->id] = $post->likes_count ?? 0;
        }

        return view('livewire.blog.post-list', [
            'posts' => $posts,
        ])->layout('layouts.livewireLayout');
    }



}
