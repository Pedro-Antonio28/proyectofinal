<div class="bg-white rounded-3xl overflow-hidden shadow-xl transform transition-all hover:scale-[1.02] flex flex-col justify-between"
     wire:key="post-{{ $postId }}">

    @if ($image)
        <div class="relative group">
            <img src="{{ asset('storage/' . $image) }}"
                 alt="{{ $title }}"
                 class="w-full h-72 object-cover group-hover:brightness-75 transition duration-300 rounded-t-3xl">
            <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/60 via-black/10 to-transparent px-5 py-3">
                <h3 class="text-lg font-bold text-white leading-tight text-center line-clamp-2">
                    {{ $title }}
                </h3>
            </div>
        </div>
    @else
        <div class="bg-gray-200 h-72 flex items-center justify-center">
            <span class="text-gray-600">Sin imagen</span>
        </div>
        <div class="p-4">
            <h3 class="text-xl font-bold text-gray-900 text-center">{{ $title }}</h3>
        </div>
    @endif

    <div class="px-6 py-4">
        <div class="grid grid-cols-2 gap-y-4 gap-x-4 text-gray-700 text-sm font-semibold text-center">
            <div class="flex flex-col items-center">
                <div class="text-xl">🔥</div>
                <span>{{ $macroData['calories'] ?? '—' }} kcal</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="text-xl">💪</div>
                <span>{{ $macroData['protein'] ?? '—' }} g proteína</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="text-xl">🍞</div>
                <span>{{ $macroData['carbs'] ?? '—' }} g carbohidratos</span>
            </div>
            <div class="flex flex-col items-center">
                <div class="text-xl">🥑</div>
                <span>{{ $macroData['fat'] ?? '—' }} g grasa</span>
            </div>
        </div>

        <div class="mt-4 flex items-center justify-center gap-2">
            @auth
                <button wire:click="like"
                        class="text-pink-600 hover:text-pink-800 text-2xl transition transform active:scale-125">
                    ❤️
                </button>
                <span class="text-sm text-gray-600">
                    {{ $likes }} me gusta
                </span>
            @else
                <span class="text-sm text-gray-400 italic">Inicia sesión para dar like</span>
            @endauth
        </div>
    </div>

    <div class="pb-4 px-6">
        <div class="flex justify-center gap-3">
            <a href="{{ route('posts.edit', $postId) }}">
                <x-button size="sm">Editar</x-button>
            </a>
            <x-button size="sm" wire:click="$emitUp('deletePost', {{ $postId }})" class="bg-red-600 hover:bg-red-700">
                Eliminar
            </x-button>
        </div>
    </div>
</div>
