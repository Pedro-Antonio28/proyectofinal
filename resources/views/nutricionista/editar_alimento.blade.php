@extends('layouts.CalorixLayout')

@section('content')
    <div class="container mx-auto mt-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">{{ __('messages.edit_food') }}</h1>

        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-300 max-w-lg mx-auto">
            <form action="{{ route('nutricionista.dieta.update', [
                'clienteId' => $clienteId,
                'dia' => $dia,
                'tipoComida' => $tipoComida,
                'alimentoId' => $alimentoSeleccionado['alimento_id']
            ]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">{{ __('messages.food_name') }}:</label>
                    <input type="text" value="{{ $alimentoSeleccionado['nombre'] }}"
                           class="w-full px-4 py-2 border border-gray-300 bg-gray-200 rounded-lg" disabled>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">{{ __('messages.amount_g') }}:</label>
                    <input type="number" name="cantidad" value="{{ $alimentoSeleccionado['cantidad'] }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded w-full">
                    ✅ {{ __('messages.save_changes') }}
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('nutricionista.cliente.dieta', $clienteId) }}"
                   class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    🔙 {{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>
@endsection
