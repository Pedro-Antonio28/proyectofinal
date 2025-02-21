<div class="flex justify-center items-center min-h-screen bg-[#f8fff4]">
    <div class="bg-white shadow-xl rounded-2xl p-8 border border-gray-300 w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">➕ Agregar Alimento</h2>

        <div class="space-y-4">
            <div>
                <label class="block text-gray-700 font-semibold">Nombre del alimento</label>
                <input type="text" wire:model="nombre" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Ej: Pollo a la plancha">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold">Calorías</label>
                <input type="number" wire:model="calorias" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="Calorías por 100g">
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold">Proteínas (g)</label>
                    <input type="number" wire:model="proteinas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="0">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold">Carbohidratos (g)</label>
                    <input type="number" wire:model="carbohidratos" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="0">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold">Grasas (g)</label>
                    <input type="number" wire:model="grasas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500" placeholder="0">
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-between">
            <button wire:click="guardar" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                Guardar
            </button>
            <a href="{{ route('dashboard') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                Cancelar
            </a>
        </div>
    </div>
</div>
