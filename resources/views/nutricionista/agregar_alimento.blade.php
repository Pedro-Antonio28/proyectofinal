@extends('layouts.CalorixLayout')

@section('content')
    <div class="container mx-auto mt-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">{{ __('messages.add_food') }}</h1>

        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-300 max-w-lg mx-auto">
            <form action="{{ route('nutricionista.dieta.add', $cliente->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">{{ __('messages.food_name') }}:</label>
                    <input type="text" name="nombre" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">{{ __('messages.calories_label') }} ({{ __('messages.calories_placeholder') }}):</label>
                    <input type="number" step="0.1" name="calorias" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">{{ __('messages.proteins_label') }}:</label>
                    <input type="number" step="0.1" name="proteinas" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">{{ __('messages.carbs_label') }}:</label>
                    <input type="number" step="0.1" name="carbohidratos" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">{{ __('messages.fats_label') }}:</label>
                    <input type="number" step="0.1" name="grasas" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">{{ __('messages.amount_g') }}:</label>
                    <input type="number" step="1" name="cantidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">{{ __('messages.day_of_week') }}:</label>
                    <select name="dia" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="Lunes">{{ __('messages.monday') }}</option>
                        <option value="Martes">{{ __('messages.tuesday') }}</option>
                        <option value="Miércoles">{{ __('messages.wednesday') }}</option>
                        <option value="Jueves">{{ __('messages.thursday') }}</option>
                        <option value="Viernes">{{ __('messages.friday') }}</option>
                        <option value="Sábado">{{ __('messages.saturday') }}</option>
                        <option value="Domingo">{{ __('messages.sunday') }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">{{ __('messages.meal_type') }}:</label>
                    <select name="tipo_comida" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="Desayuno">{{ __('messages.breakfast') }}</option>
                        <option value="Almuerzo">{{ __('messages.morning_snack') }}</option>
                        <option value="Comida">{{ __('messages.lunch') }}</option>
                        <option value="Merienda">{{ __('messages.afternoon_snack') }}</option>
                        <option value="Cena">{{ __('messages.dinner') }}</option>
                    </select>
                </div>

                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full">
                    ✅ {{ __('messages.save') }}
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('nutricionista.cliente.dieta', $cliente->id) }}"
                   class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    🔙 {{ __('messages.back') }}
                </a>
            </div>
        </div>
    </div>
@endsection
