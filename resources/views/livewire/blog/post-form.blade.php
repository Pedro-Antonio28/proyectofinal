<div class="max-w-4xl mx-auto p-6 bg-white dark:bg-gray-900 shadow-xl rounded-2xl space-y-6">

    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">
        {{ $post->id ? __('Editar dieta del blog') : __('Nueva dieta del blog') }}
    </h2>

    {{-- Título --}}
    <div>
        <x-label for="title" :value="__('Título de la dieta')" />
        <x-input id="title" type="text" wire:model.defer="post.title" class="mt-1 block w-full" />
        @error('post.title') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Descripción (WYSIWYG) --}}
    <div wire:ignore>
        <x-label for="description" :value="__('Descripción')" />
        <textarea
            id="description"
            x-data
            x-init="
                ClassicEditor
                    .create($el)
                    .then(editor => {
                        editor.model.document.on('change:data', () => {
                            @this.set('post.description', editor.getData())
                        });
                    });
            "
            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white"
        >{!! $post->description !!}</textarea>
        @error('post.description') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Macronutrientes --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <x-label for="macros.calories" :value="__('Calorías (kcal)')" />
            <x-input id="macros.calories" type="number" wire:model.defer="post.macros.calories" min="0" step="1" />
            @error('post.macros.calories') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <x-label for="macros.protein" :value="__('Proteínas (g)')" />
            <x-input id="macros.protein" type="number" wire:model.defer="post.macros.protein" min="0" step="0.1" />
            @error('post.macros.protein') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Imagen (Drag & Drop o input normal) --}}
    <div x-data="{ isDragging: false }" class="relative border-2 border-dashed rounded-xl p-4 dark:border-gray-700 transition"
         :class="{ 'border-blue-500 bg-blue-50 dark:bg-gray-800': isDragging }"
         @dragover.prevent="isDragging = true"
         @dragleave.prevent="isDragging = false"
         @drop.prevent="isDragging = false">

        <x-label for="image" :value="__('Imagen de portada (opcional)')" />
        <input id="image" type="file" wire:model="image" class="mt-2" />

        @if ($image)
            <div class="mt-4">
                <img src="{{ $image->temporaryUrl() }}" class="max-h-48 rounded shadow-md" alt="Preview" />
            </div>
        @elseif ($post->image_path)
            <div class="mt-4">
                <img src="{{ asset('storage/' . $post->image_path) }}" class="max-h-48 rounded shadow-md" alt="Actual" />
            </div>
        @endif

        @error('image') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Botón --}}
    <div class="pt-4 flex justify-end">
        <x-button wire:click="save" wire:loading.attr="disabled" class="bg-green-600 hover:bg-green-700">
            <svg wire:loading wire:target="save" class="animate-spin h-5 w-5 mr-2 text-white" fill="none"
                 viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            {{ $post->id ? __('Actualizar') : __('Publicar dieta') }}
        </x-button>
    </div>
</div>
