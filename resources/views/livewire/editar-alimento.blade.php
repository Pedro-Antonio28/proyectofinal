<div class="flex justify-center items-center min-h-screen bg-[#f8fff4]">
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-300 w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">✏️ Editar Alimento</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-gray-700 font-semibold">Nombre del alimento</label>
                <input type="text" value="{{ $alimento->alimento->nombre }}" disabled class="w-full px-4 py-2 border border-gray-300 bg-gray-200 rounded-lg">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Cantidad (g)</label>
                <input type="number" wire:model="cantidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500" placeholder="Ej: 150">
            </div>

            <div class="text-gray-700">
                <p><strong>🔥 Calorías:</strong> {{ $alimento->alimento->calorias }} kcal</p>
                <p><strong>🥩 Proteínas:</strong> {{ $alimento->alimento->proteinas }} g</p>
                <p><strong>🍞 Carbohidratos:</strong> {{ $alimento->alimento->carbohidratos }} g</p>
                <p><strong>🥑 Grasas:</strong> {{ $alimento->alimento->grasas }} g</p>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <button wire:click="actualizar" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                Guardar Cambios
            </button>
            <button wire:click="eliminar" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                Eliminar
            </button>
        </div>
        @if (session()->has('error'))
            <div class="text-red-600 bg-red-100 border border-red-500 rounded-lg p-4 text-center mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="text-red-600 bg-red-100 border border-red-500 rounded-lg p-4 text-center mb-6">
                @foreach ($errors->all() as $error)
                    <p>❌ {{ $error }}</p>
                @endforeach
            </div>
        @endif
        <div class="mt-4 text-center">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:underline">Volver</a>
        </div>
    </div>
</div>
