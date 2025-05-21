<?php

namespace App\Http\Controllers;

use App\Events\PostAddedToDiet;
use App\Models\Post;
use Illuminate\Http\Request;

class PublicBlogController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->whereNull('deleted_at')->paginate(12);
        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return view('blog.show', compact('post'));
    }

    public function addToDiet(Post $post)
    {
        $user = auth()->user();

        if ($user->posts()->where('post_id', $post->id)->exists()) {
            return back()->with('info', 'Esta dieta ya está en tu perfil.');
        }

        $user->posts()->attach($post->id, [
            'added_at' => now(),
            'custom_notes' => null,
        ]);

        event(new PostAddedToDiet($user, $post));

        return back()->with('success', 'Dieta añadida correctamente a tu perfil.');
    }
}
