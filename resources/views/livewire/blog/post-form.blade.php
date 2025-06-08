<div class="max-w-3xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-bold mb-8 text-center">➕ {{ __('messages.add_new_recipe') }}</h1>

    <form wire:submit.prevent="guardarPost" class="space-y-6">
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validaciones --}}
        @error('post.title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        @error('post.description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        @error('macrosData.calories') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
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

        {{-- Título y descripción --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('messages.title') }}</label>
            <input type="text" wire:model.defer="post.title" class="w-full border rounded p-2 mt-1" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">{{ __('messages.description') }}</label>
            <textarea wire:model.defer="post.description" rows="5" class="w-full border rounded p-2 mt-1" required></textarea>
        </div>

        {{-- Ingredientes --}}
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">{{ __('messages.ingredients') }}</label>
            @foreach($ingredients as $index => $ingredient)
                <div class="flex gap-2">
                    <input type="text" wire:model="ingredients.{{ $index }}.name" placeholder="{{ __('messages.name') }}" class="flex-1 border rounded p-2" required>
                    <input type="text" wire:model="ingredients.{{ $index }}.quantity" placeholder="{{ __('messages.quantity') }}" class="w-32 border rounded p-2" required>
                    <button type="button" wire:click="removeIngredient({{ $index }})" class="text-red-600 hover:underline">
                        {{ __('messages.remove') }}
                    </button>
                </div>
            @endforeach
            <button type="button" wire:click="addIngredient" class="text-green-600 hover:underline mt-2">
                ➕ {{ __('messages.add_ingredient') }}
            </button>
        </div>

        {{-- Macros --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('messages.calories') }}</label>
                <input type="number" wire:model.defer="macrosData.calories" class="w-full border rounded p-2 mt-1" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('messages.proteins') }}</label>
                <input type="number" step="0.1" wire:model.defer="macrosData.protein" class="w-full border rounded p-2 mt-1" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('messages.carbohydrates') }}</label>
                <input type="number" step="0.1" wire:model.defer="macrosData.carbs" class="w-full border rounded p-2 mt-1" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">{{ __('messages.fats') }}</label>
                <input type="number" step="0.1" wire:model.defer="macrosData.fat" class="w-full border rounded p-2 mt-1" required>
            </div>
        </div>

        {{-- Imágenes --}}
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('messages.post_images') }}</label>
            <input type="file" multiple wire:model="images" class="w-full border border-dashed border-gray-300 rounded p-4 cursor-pointer transition hover:border-green-500 hover:bg-green-50" />
            <div wire:loading wire:target="images" class="text-sm text-gray-500 mt-2">
                📤 {{ __('messages.uploading_images') }}
            </div>
            @error('imagesTemp.*') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror

            @if ($imagesTemp)
                <p class="text-sm text-gray-600 mt-2">{{ __('messages.images_added', ['count' => count($imagesTemp)]) }}</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-4">
                    @foreach ($imagesTemp as $img)
                        <img src="{{ $img->temporaryUrl() }}" class="w-full h-40 object-cover rounded shadow">
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Botón guardar --}}
        <div class="text-center pt-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-full shadow-lg transition">
                💾 {{ __('messages.save_recipe') }}
            </button>
        </div>
    </form>
</div>
