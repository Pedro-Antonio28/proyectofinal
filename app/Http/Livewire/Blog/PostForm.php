<?php

namespace App\Http\Livewire\Blog;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;

class PostForm extends Component
{
    use WithFileUploads;

    public $post = [
        'title' => '',
        'description' => '',
    ];

    public $images = [];
    public $imagesTemp = [];

    public $ingredients = [];
    public $macrosData = [
        'calories' => null,
        'protein' => null,
        'carbs' => null,
        'fat' => null,
    ];

    public ?Post $editingPost = null;

    public function mount($post = null)
    {
        if ($post instanceof Post) {
            $this->editingPost = $post;
            $this->post = [
                'title' => $post->title,
                'description' => $post->description,
            ];
            $this->macrosData = $post->macros ?? [];
            $this->ingredients = $post->ingredients ?? [['name' => '', 'quantity' => '']];
        } else {
            $this->ingredients = [['name' => '', 'quantity' => '']];
        }
    }

    public function addIngredient()
    {
        $this->ingredients[] = ['name' => '', 'quantity' => ''];
    }

    public function removeIngredient($index)
    {
        unset($this->ingredients[$index]);
        $this->ingredients = array_values($this->ingredients);
    }

    public function updatedImages($value)
    {
        foreach ($value as $img) {
            $this->imagesTemp[] = $img;
        }

        $this->images = []; // limpiamos el input para permitir seleccionar más
    }

    public function guardarPost()
    {
        $this->validate([
            'post.title' => 'required|string|max:255',
            'post.description' => 'required|string',
            'macrosData.calories' => 'required|numeric|min:0',
            'macrosData.protein' => 'required|numeric|min:0',
            'macrosData.carbs' => 'required|numeric|min:0',
            'macrosData.fat' => 'required|numeric|min:0',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.quantity' => 'required|string|max:255',
            'imagesTemp' => 'array',
            'imagesTemp.*' => 'image|max:2048',
        ]);

        $post = $this->editingPost ?? new Post();
        $post->title = $this->post['title'];
        $post->description = $this->post['description'];
        $post->macros = $this->macrosData;
        $post->ingredients = $this->ingredients;
        $post->user_id = auth()->id();
        $post->save();

        if (!empty($this->imagesTemp)) {
            foreach ($this->imagesTemp as $img) {
                $path = $img->store('posts', 'public');
                $post->images()->create(['path' => $path]);
            }
        }

        session()->flash('success', 'Receta guardada correctamente ✅');
        return redirect()->route('posts.index');
    }

    public function render()
    {
        return view('livewire.blog.post-form')->layout('layouts.livewireLayout');
    }
}
