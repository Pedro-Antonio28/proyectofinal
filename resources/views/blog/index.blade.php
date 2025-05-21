@extends('layouts.CalorixLayout')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <h2 class="text-3xl font-semibold mb-6 text-gray-800 dark:text-gray-100">Blog de dietas</h2>

        <a href="{{ route('blog.export.excel') }}" class="inline-block mb-4 px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">
            📥 Exportar a Excel
        </a>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($posts as $post)
                <div class="bg-white dark:bg-gray-900 shadow rounded-xl overflow-hidden">
                    @if ($post->image_path)
                        <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                    @endif

                    <div class="p-4 space-y-2">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">
                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                        </h3>

                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">{{ strip_tags($post->description) }}</p>

                        <div class="flex justify-between text-sm text-gray-700 dark:text-gray-300 mt-2">
                            <span>Calorías: {{ $post->macros['calories'] ?? '—' }}</span>
                            <span>Proteínas: {{ $post->macros['protein'] ?? '—' }} g</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
