@extends('layouts.CalorixLayout')

@section('content')
    <div class="container mx-auto mt-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">➕ Agregar Alimento a la Dieta</h1>

        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-300 max-w-lg mx-auto">
            <form action="{{ route('nutricionista.dieta.add', $cliente->id) }}" method="POST">
                @csrf



                <!-- Nombre del alimento -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Nombre del Alimento:</label>
                    <input type="text" name="nombre" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <!-- Calorías por cada 100g -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Calorías (por 100g):</label>
                    <input type="number" step="0.1" name="calorias" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <!-- Macronutrientes -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Proteínas (g por 100g):</label>
                    <input type="number" step="0.1" name="proteinas" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Carbohidratos (g por 100g):</label>
                    <input type="number" step="0.1" name="carbohidratos" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Grasas (g por 100g):</label>
                    <input type="number" step="0.1" name="grasas" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <!-- Cantidad en gramos -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Cantidad (g):</label>
                    <input type="number" step="1" name="cantidad" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                </div>

                <!-- Día de la semana -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Día de la Semana:</label>
                    <select name="dia" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                    </select>
                </div>

                <!-- Tipo de comida -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold">Tipo de Comida:</label>
                    <select name="tipo_comida" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="Desayuno">Desayuno</option>
                        <option value="Almuerzo">Almuerzo</option>
                        <option value="Comida">Comida</option>
                        <option value="Merienda">Merienda</option>
                        <option value="Cena">Cena</option>
                    </select>
                </div>

                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full">
                    ✅ Guardar Alimento
                </button>
            </form>
        </div>
    </div>
@endsection
