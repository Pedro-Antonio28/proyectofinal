<div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg p-6 mt-12">
    <h2 class="text-2xl font-bold text-gray-800">✏️ Editar Alimento</h2>

    @if ($alimento)
        <p class="text-gray-600 mt-2">{{ $alimento['nombre'] }}</p>

        <label class="block mt-4 text-gray-700">Cantidad (g):</label>
        <input type="number" min="1" wire:model="cantidad" class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1">

        <div class="flex justify-between mt-6">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg">Volver</a>
            <button wire:click="actualizarAlimento" class="px-4 py-2 bg-green-500 text-white rounded-lg">Guardar</button>
            <button wire:click="eliminarAlimento" class="px-4 py-2 bg-red-500 text-white rounded-lg">Eliminar</button>
        </div>
    @else
        <p class="text-red-500 text-center mt-4">⚠️ Alimento no encontrado.</p>
        <a href="{{ route('dashboard') }}" class="block text-center mt-4 text-blue-500">Volver al dashboard</a>
    @endif
</div>
