<?php

namespace App\Http\Livewire\Blog;

use App\Models\Post;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostForm extends Component
{
    use WithFileUploads;

    public Post $post;
    public $image;

    protected function rules()
    {
        return [
            'post.title' => 'required|string|max:255',
            'post.description' => 'required|string',
            'post.macros.calories' => 'required|numeric|min:0',
            'post.macros.protein' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function mount(Post $post = null)
    {
        $this->post = $post ?? new Post();
    }

    public function save()
    {
        $this->validate();

        $this->post->user_id = auth()->id();
        $this->post->slug = Str::slug($this->post->title);

        if ($this->image) {
            $this->post->image_path = $this->image->store('posts', 'public');
        }

        $this->post->save();

        session()->flash('success', __('Post guardado correctamente'));
        return redirect()->route('posts.index');
    }

    public function render()
    {
        return view('livewire.blog.post-form');
    }
}
