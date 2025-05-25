<?php

namespace App\Http\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;

class PostCard extends Component
{
    public int $postId;
    public string $title;
    public ?string $image;
    public array $macroData = [];
    public int $likes;

    public function mount(Post $post)
    {
        $this->postId = $post->id;
        $this->title = $post->title;
        $this->image = $post->image_path;
        $this->macroData = $post->macros ?? [];
        $this->likes = $post->likes()->count();
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


    public function render()
    {
        return view('livewire.blog.post-card');
    }
}
