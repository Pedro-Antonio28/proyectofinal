<?php

namespace App\Http\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;

class PostCard extends Component
{
    public int $postId;

    public string $title;
    public ?string $image;
    public array $images = [];
    public array $macroData = [];
    public int $likes;

    public bool $mostrarNota = false;

    public int $postUserId;

    public function mount(Post $post, $mostrarNota = false)
    {
        $this->postId = $post->id;
        $this->title = $post->title;
        $this->image = $post->image_path;
        $this->images = $post->images->pluck('path')->toArray();
        $this->macroData = $post->macros ?? [];
        $this->likes = $post->likes()->count();
        $this->postUserId = $post->user_id;
        $this->mostrarNota = $mostrarNota;
    }




    public function like()
    {
        $user = auth()->user();
        $post = Post::findOrFail($this->postId);
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
        } else {
            $post->likes()->create(['user_id' => $user->id]);



        }

        $this->likes = $post->likes()->count();
    }

    public function eliminarPost()
    {
        $this->dispatch('deletePost', $this->postId);
    }




    public function render()
    {
        return view('livewire.blog.post-card');
    }
}
