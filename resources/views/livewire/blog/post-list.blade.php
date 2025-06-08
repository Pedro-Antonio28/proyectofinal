<div class="max-w-6xl mx-auto px-4 py-12">
    <h2 class="text-4xl font-extrabold text-center text-gray-900 mb-12" data-aos="fade-down">
        🥗 {{ __('messages.blog_diets') }}
    </h2>

    <div class="mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <input
            type="text"
            wire:model="ingrediente"
            placeholder="🔍 {{ __('messages.search_by_ingredient') }}"
            class="border px-4 py-2 rounded w-full sm:w-1/2"
        >
        <button
            wire:click="buscarPorIngrediente"
            class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition"
        >
            {{ __('messages.search') }}
        </button>
        <button
            type="button"
            onclick="window.location.href = '?mostrarFavoritos={{ $mostrarFavoritos ? '0' : '1' }}'"
            class="px-4 py-2 rounded shadow font-semibold transition
           {{ $mostrarFavoritos ? 'bg-yellow-500 text-white' : 'bg-gray-300 hover:bg-gray-400' }}">
            {{ $mostrarFavoritos ? '🔙 ' . __('messages.show_all') : '⭐ ' . __('messages.show_favorites_only') }}
        </button>
    </div>

    <div class="text-center mb-10">
        <a href="{{ route('posts.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white font-semibold rounded-full shadow hover:bg-green-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('messages.add_new_recipe') }}
        </a>
    </div>

    {{-- ⚡ AOS solo aquí: en envoltorio que no se actualiza --}}
    <div data-aos="zoom-in">
        {{-- Contenedor Livewire reactivo sin AOS --}}
        <div class="grid gap-10 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-2">
            @foreach ($posts as $post)
                <livewire:blog.post-card
                    :post="$post"
                    :mostrarNota="$mostrarFavoritos"
                    :wire:key="'post-'.$post->id"
                />
            @endforeach

            @if ($posts->isEmpty())
                <div class="text-center text-gray-500 col-span-full" data-aos="fade-up">
                    {{ __('messages.no_diets_available') }} 🥲
                </div>
            @endif
        </div>
    </div>

    <div class="mt-12">
        {{ $posts->links() }}
    </div>
</div>
