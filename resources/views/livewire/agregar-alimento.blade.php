<div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg p-6 mt-12">
    <h2 class="text-2xl font-bold text-gray-800">➕ Agregar Alimento a {{ ucfirst($tipoComida) }}</h2>

    <label class="block mt-4 text-gray-700">Selecciona un alimento:</label>
    <select wire:model="alimentoSeleccionado" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1">
        <option value="">-- Seleccionar --</option>
        @foreach ($alimentos as $alimento)
            <option value="{{ $alimento->id }}">{{ $alimento->nombre }}</option>
        @endforeach
    </select>

    <label class="block mt-4 text-gray-700">Cantidad (g):</label>
    <input type="number" min="1" wire:model="cantidad" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1">

    <div class="flex justify-between mt-6">
        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg">Volver</a>
        <button wire:click="agregarAlimento" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Añadir</button>
    </div>
</div>
