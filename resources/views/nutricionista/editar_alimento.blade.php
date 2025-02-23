@extends('layouts.CalorixLayout')

@section('content')
    <div class="container mx-auto mt-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">✏️ Editar Alimento en la Dieta</h1>

        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-300 max-w-lg mx-auto">
            <form action="{{ route('nutricionista.dieta.update', [
            'clienteId' => $clienteId,
            'dia' => $dia,
            'tipoComida' => $tipoComida,
            'alimentoId' => $alimentoSeleccionado['alimento_id']
        ]) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nombre del alimento (No editable) -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Nombre del Alimento:</label>
                    <input type="text" value="{{ $alimentoSeleccionado['nombre'] }}" class="w-full px-4 py-2 border border-gray-300 bg-gray-200 rounded-lg" disabled>
                </div>

                <!-- Cantidad en gramos -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Cantidad (g):</label>
                    <input type="number" name="cantidad" value="{{ $alimentoSeleccionado['cantidad'] }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded w-full">
                    ✅ Guardar Cambios
                </button>
            </form>
        </div>
    </div>
@endsection
