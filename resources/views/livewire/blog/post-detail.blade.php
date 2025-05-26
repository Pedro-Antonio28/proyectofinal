<div class="max-w-4xl mx-auto px-4 py-12" data-aos="fade-up" data-aos-duration="800">

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
    @if ($post->image_path)
        <div data-aos="zoom-in" class="rounded-2xl overflow-hidden shadow-lg mb-10">
            <img src="{{ asset('storage/' . $post->image_path) }}"
                 alt="{{ $post->title }}"
                 class="w-full h-80 object-cover transition duration-300 hover:brightness-90">
        </div>
    @endif

    {{-- Ingredientes primero --}}
    @if (!empty($post->ingredients))
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl p-6 shadow-md mb-10" data-aos="fade-up">
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
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl p-6 shadow-md mb-10" data-aos="fade-up" data-aos-delay="100">
        <div class="text-lg text-gray-800 leading-relaxed whitespace-pre-line">
            {!! nl2br(e($post->description)) !!}
        </div>
    </div>

    {{-- Macros por 100g --}}
    <div class="bg-white/80 backdrop-blur-lg rounded-2xl p-6 shadow-md mb-12" data-aos="fade-up">
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
        <x-button wire:click="lanzarModalAñadir({{ $post->id }})">
            ➕ Añadir a mi dieta
        </x-button>

        @if($mostrarModal)
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

                    {{-- NUEVO: campo para cantidad --}}
                    <div>
                        <label class="block text-sm font-semibold">Cantidad (en gramos):</label>
                        <input type="number" min="1" wire:model="cantidadSeleccionada" placeholder="Ej: 150"
                               class="w-full mt-1 border rounded p-2" />
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <x-button wire:click="$set('mostrarModal', false)" class="bg-gray-500 hover:bg-gray-600">Cancelar</x-button>
                        <x-button wire:click="guardarPostEnDieta" class="bg-green-600 hover:bg-green-700">Añadir</x-button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
