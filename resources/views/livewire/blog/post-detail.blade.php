<div class="max-w-4xl mx-auto px-4 py-12">


{{-- Botón de volver estilizado --}}
    <div class="mb-8">
        <a href="{{ route('posts.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 bg-[#a7d675] px-4 py-2 rounded-full shadow hover:bg-gray-100 transition hover:-translate-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
            </svg>
            Volver al listado de dietas
        </a>
    </div>

    {{-- Título --}}
    <h1 class="text-4xl font-extrabold text-center text-gray-900 mb-10 tracking-tight" data-aos="fade-down">
        {{ $post->title }}
    </h1>

    {{-- Imagen destacada --}}
    @if ($post->images && $post->images->count())
        <div x-data="{
    current: 0,
    fullscreen: false,
    fullscreenImg: '',
    images: @js($post->images->map(fn($img) => asset('storage/' . $img->path)))
}" class="relative w-full max-w-3xl mx-auto mb-10">

        <div class="overflow-hidden rounded-xl shadow-md">
                <template x-for="(img, index) in images" :key="index">
                    <div x-show="current === index" class="transition duration-500 ease-in-out">
                        <img :src="img" class="w-full max-h-96 object-contain rounded-xl cursor-zoom-in" @click="fullscreen = true; fullscreenImg = img">

                    </div>
                </template>
            </div>

            {{-- Modal fullscreen --}}
            <div x-show="fullscreen"
                 x-transition
                 class="fixed inset-0 z-40 bg-black bg-opacity-90 flex items-center justify-center"
                 @click.away="fullscreen = false"
                 @keydown.escape.window="fullscreen = false"
                 x-cloak>
                <img :src="fullscreenImg" class="max-h-screen max-w-full object-contain shadow-xl rounded">
                <button @click="fullscreen = false"
                        class="absolute top-5 right-5 text-white text-3xl hover:text-red-500">
                    &times;
                </button>
            </div>


            {{-- Flechas --}}
            <button @click="current = (current - 1 + images.length) % images.length"
                    class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-white bg-opacity-75 p-2 rounded-full shadow hover:bg-opacity-100">
                ◀
            </button>
            <button @click="current = (current + 1) % images.length"
                    class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-white bg-opacity-75 p-2 rounded-full shadow hover:bg-opacity-100">
                ▶
            </button>
        </div>
    @endif




    {{-- Ingredientes primero --}}
    @if (!empty($post->ingredients))
        <div class="bg-white rounded-2xl p-6 shadow-md mb-10" data-aos="fade-up">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">📝 Ingredientes</h2>
            <ul class="list-disc list-inside text-gray-700 space-y-2 text-lg">
                @foreach ($post->ingredients as $ingredient)
                    <li>
                        {{ $ingredient['quantity'] ?? '' }} {{ $ingredient['name'] }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Descripción --}}
    <div class="bg-white rounded-2xl p-6 shadow-md mb-10" data-aos="fade-up" data-aos-delay="100">
        <div class="text-lg text-gray-800 leading-relaxed whitespace-pre-line">
            {!! nl2br(e($post->description)) !!}
        </div>
    </div>

    {{-- Macros por 100g --}}
    <div class="bg-white rounded-2xl p-6 shadow-md mb-10" data-aos="fade-up">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">⚖️ Valores nutricionales por cada 100g</h2>

        @php
            $macros = [
                ['icon' => '🔥', 'label' => 'Calorías', 'value' => $post->macros['calories'] ?? '—', 'unit' => 'kcal'],
                ['icon' => '💪', 'label' => 'Proteínas', 'value' => $post->macros['protein'] ?? '—', 'unit' => 'g'],
                ['icon' => '🍞', 'label' => 'Carbohidratos', 'value' => $post->macros['carbs'] ?? '—', 'unit' => 'g'],
                ['icon' => '🥑', 'label' => 'Grasas', 'value' => $post->macros['fat'] ?? '—', 'unit' => 'g'],
            ];
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center text-gray-700">
            @foreach ($macros as $macro)
                <div class="bg-gray-100 rounded-xl p-5 shadow-sm hover:scale-105 transition duration-200">
                    <div class="text-3xl mb-2">{{ $macro['icon'] }}</div>
                    <div class="font-semibold text-lg">{{ $macro['value'] }} {{ $macro['unit'] }}</div>
                    <div class="text-sm text-gray-600">{{ $macro['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Botón principal --}}
    <div class="text-center" data-aos="fade-up" data-aos-delay="300">
        @if(auth()->user()?->is_premium)
            <x-button wire:click="lanzarModalAñadir({{ $post->id }})">
                ➕ Añadir a mi dieta
            </x-button>
        @else
            <button type="button"
                    onclick="abrirModalPremium()"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-semibold shadow transition">
                ➕ Añadir a mi dieta
            </button>
        @endif
    </div>

    {{-- Modal de añadir dieta (Livewire) --}}
    @if(auth()->user()?->is_premium && $mostrarModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:ignore.self>
            <div class="bg-white rounded-xl p-6 w-full max-w-md space-y-4 shadow-xl">
                <h2 class="text-xl font-bold text-center">¿Dónde quieres añadir esta receta?</h2>

                <div>
                    <label class="block text-sm font-semibold">Día:</label>
                    <select wire:model="diaSeleccionado" class="w-full mt-1 border rounded p-2">
                        @foreach(['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                            <option value="{{ $dia }}">{{ $dia }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Tipo de comida:</label>
                    <select wire:model="tipoComidaSeleccionado" class="w-full mt-1 border rounded p-2">
                        @foreach(['Desayuno','Comida','Merienda','Cena'] as $tipo)
                            <option value="{{ $tipo }}">{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Cantidad (en gramos):</label>
                    <input type="number" min="1" wire:model="cantidadSeleccionada" placeholder="Ej: 150"
                           class="w-full mt-1 border rounded p-2" />
                </div>

                <div>
                    <label class="block text-sm font-semibold">¿Añadir a favoritos?</label>
                    <input type="checkbox" wire:model="esFavorito" class="mt-1">
                </div>

                <div>
                    <label class="block text-sm font-semibold">Nota personal (opcional):</label>
                    <textarea wire:model="notaPersonal" rows="3" class="w-full mt-1 border rounded p-2" placeholder="Puedes escribir algo como: 'Muy buena después del entreno'..."></textarea>
                </div>


                <div class="flex justify-end gap-2 pt-4">
                    <x-button wire:click="$set('mostrarModal', false)" class="bg-gray-500 hover:bg-gray-600">Cancelar</x-button>
                    <x-button wire:click="guardarPostEnDieta" class="bg-green-600 hover:bg-green-700">Añadir</x-button>
                </div>
            </div>
        </div>
    @endif
    @include('components.modal-premium')

</div>
