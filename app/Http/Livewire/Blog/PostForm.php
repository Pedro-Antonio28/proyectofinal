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

    public $image;
    public $ingredients = [];
    public $macrosData = [
        'calories' => null,
        'protein' => null,
        'carbs' => null,
        'fat' => null,
    ];

    public function mount()
    {
        $this->ingredients = [
            ['name' => '', 'quantity' => '']
        ];
    }

    public function addIngredient()
    {
        $this->ingredients[] = ['name' => '', 'quantity' => ''];
    }

    public function removeIngredient($index)
    {
        unset($this->ingredients[$index]);
        $this->ingredients = array_values($this->ingredients); // Reindexar
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
            'image' => 'nullable|image|max:2048',
            'ingredients.*.name' => 'required|string|max:255',
            'ingredients.*.quantity' => 'required|string|max:255',
        ]);

        $nuevoPost = new Post();
        $nuevoPost->title = $this->post['title'];
        $nuevoPost->description = $this->post['description'];
        $nuevoPost->macros = $this->macrosData;
        $nuevoPost->ingredients = $this->ingredients;
        $nuevoPost->user_id = auth()->id();


        if ($this->image) {
            $imagePath = $this->image->store('posts', 'public');
            $nuevoPost->image_path = $imagePath;
        }

        $nuevoPost->save();

        session()->flash('success', 'Receta guardada correctamente ✅');

        return redirect()->route('posts.index');
    }

    public function render()
    {
        return view('livewire.blog.post-form')->layout('layouts.livewireLayout');
    }
}
