<div class="max-w-3xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-8 text-center">➕ Añadir nueva receta</h1>

    <form wire:submit.prevent="guardarPost" class="space-y-6">
        {{-- Mensaje de éxito --}}
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Errores de validación --}}
        @error('post.title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        @error('post.description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        @error('post.macros.calories') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

        {{-- Validación de cada ingrediente --}}
        @foreach($errors->get('ingredients.*.name') as $messages)
            @foreach($messages as $message)
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @endforeach
        @endforeach

        @foreach($errors->get('ingredients.*.quantity') as $messages)
            @foreach($messages as $message)
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @endforeach
        @endforeach

        {{-- Título --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Título</label>
            <input type="text" wire:model.defer="post.title" class="w-full border rounded p-2 mt-1" required>
        </div>

        {{-- Descripción --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea wire:model.defer="post.description" rows="5" class="w-full border rounded p-2 mt-1" required></textarea>
        </div>

        {{-- Ingredientes dinámicos --}}
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Ingredientes</label>
            @foreach($ingredients as $index => $ingredient)
                <div class="flex gap-2">
                    <input type="text" wire:model="ingredients.{{ $index }}.name" placeholder="Nombre" class="flex-1 border rounded p-2" required>
                    <input type="text" wire:model="ingredients.{{ $index }}.quantity" placeholder="Cantidad" class="w-32 border rounded p-2" required>
                    <button type="button" wire:click="removeIngredient({{ $index }})" class="text-red-600 hover:underline">Eliminar</button>
                </div>
            @endforeach

            <button type="button" wire:click="addIngredient" class="text-green-600 hover:underline mt-2">➕ Añadir ingrediente</button>
        </div>

        {{-- Macros por 100g --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Calorías (kcal / 100g)</label>
                <input type="number" wire:model.defer="macrosData.calories" class="w-full border rounded p-2 mt-1" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Proteínas (g / 100g)</label>
                <input type="number" step="0.1" wire:model.defer="macrosData.protein" class="w-full border rounded p-2 mt-1" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Carbohidratos (g / 100g)</label>
                <input type="number" step="0.1" wire:model.defer="macrosData.carbs" class="w-full border rounded p-2 mt-1" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Grasas (g / 100g)</label>
                <input type="number" step="0.1" wire:model.defer="macrosData.fat" class="w-full border rounded p-2 mt-1" required>
            </div>
        </div>

        {{-- Imagen --}}
        {{-- Imagen con drag and drop --}}
        <div
            x-data="{ isDropping: false }"
            x-on:dragover.prevent="isDropping = true"
            x-on:dragleave.prevent="isDropping = false"
            x-on:drop.prevent="
        isDropping = false;
        $refs.input.files = event.dataTransfer.files;
        $dispatch('input', $refs.input.files)
    "
            class="mt-4 border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition-all"
            :class="{ 'border-green-500 bg-green-50': isDropping, 'border-gray-300': !isDropping }"
        >
            <p class="text-gray-600">Arrastra una imagen aquí o haz clic para seleccionarla</p>

            <input x-ref="input" type="file" wire:model="image" class="hidden" id="imageUpload" />
            <label for="imageUpload" class="block mt-2 text-sm font-medium text-green-700 hover:underline cursor-pointer">
                Seleccionar imagen
            </label>

            {{-- Loading mientras sube --}}
            <div wire:loading wire:target="image" class="text-sm text-gray-500 mt-2">
                📤 Subiendo imagen...
            </div>

            {{-- Imagen subida + mensaje --}}
            <div wire:loading.remove wire:target="image">
                @if ($image)
                    <div class="text-green-600 text-sm mt-2">✅ Imagen lista</div>
                    <img src="{{ $image->temporaryUrl() }}" class="mx-auto mt-4 max-h-48 rounded shadow">
                @endif
            </div>
        </div>




        {{-- Botón --}}
        <div class="text-center pt-4">
            <x-button type="submit" class="bg-green-600 hover:bg-green-700 px-6 py-3 rounded-full shadow-md">
                Guardar receta
            </x-button>
        </div>
    </form>
</div>
