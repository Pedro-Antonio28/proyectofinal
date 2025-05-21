@extends('layouts.CalorixLayout')

@section('content')
    <div class="max-w-4xl mx-auto py-6 px-4">
        <div class="bg-white dark:bg-gray-900 shadow-xl rounded-2xl overflow-hidden">
            @if ($post->image_path)
                <img src="{{ asset('storage/' . $post->image_path) }}" class="w-full h-64 object-cover">
            @endif

            <div class="p-6">
                <h1 class="text-3xl font-bold mb-2 text-gray-800 dark:text-gray-100">{{ $post->title }}</h1>

                <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Publicado por: {{ $post->user->name }}
                </div>

                <div class="prose dark:prose-invert max-w-none">
                    {!! $post->description !!}
                </div>

                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div><strong>Calorías:</strong> {{ $post->macros['calories'] ?? '—' }} kcal</div>
                    <div><strong>Proteínas:</strong> {{ $post->macros['protein'] ?? '—' }} g</div>
                </div>

                @auth
                    <form action="{{ route('blog.add-to-diet', $post) }}" method="POST" class="mt-6">
                        @csrf
                        <x-button class="bg-blue-600 hover:bg-blue-700">
                            Añadir a mi dieta
                        </x-button>
                    </form>
                @else
                    <div class="mt-6 text-sm text-gray-500">
                        <a href="{{ route('login') }}" class="underline">Inicia sesión</a> para añadir esta dieta.
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection
