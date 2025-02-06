<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuestionario - Calorix</title>
    @vite('resources/css/app.css')
</head>
<body class="flex items-center justify-center min-h-screen bg-[#f8fff4]">

<div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

    <h2 class="text-xl font-semibold text-gray-900 mb-4">Completa tu información</h2>

    <form action="{{ route('questionnaire.store') }}" method="POST">
        @csrf

        <!-- Campo oculto para el paso actual -->
        <input type="hidden" name="step" value="{{ $step ?? 1 }}">

        <!-- Pregunta 1: Género -->
        <div class="{{ $step == 1 ? 'block' : 'hidden' }}">
            <label class="block text-gray-700">¿Cuál es tu género?</label>
            <select name="gender" class="w-full px-4 py-2 border rounded-lg">
                <option value="" disabled selected>Selecciona tu género</option>
                <option value="male" {{ session('gender') == 'male' ? 'selected' : '' }}>Hombre</option>
                <option value="female" {{ session('gender') == 'female' ? 'selected' : '' }}>Mujer</option>
            </select>
        </div>

        <!-- Pregunta 2: Edad -->
        <div class="{{ $step == 2 ? 'block' : 'hidden' }}">
            <label class="block text-gray-700">¿Cuál es tu edad?</label>
            <input type="number" name="age" class="w-full px-4 py-2 border rounded-lg"
                   min="10" max="100" value="{{ session('age') }}">
        </div>

        <!-- Pregunta 3: Peso -->
        <div class="{{ $step == 3 ? 'block' : 'hidden' }}">
            <label class="block text-gray-700">¿Cuál es tu peso actual? (kg)</label>
            <input type="number" name="peso" class="w-full px-4 py-2 border rounded-lg"
                   min="30" max="300" value="{{ session('peso') }}">
        </div>

        <!-- Pregunta 4: Altura -->
        <div class="{{ $step == 4 ? 'block' : 'hidden' }}">
            <label class="block text-gray-700">¿Cuál es tu altura? (cm)</label>
            <input type="number" name="altura" class="w-full px-4 py-2 border rounded-lg"
                   min="100" max="250" value="{{ session('altura') }}">
        </div>

        <!-- Pregunta 5: Objetivo -->
        <div class="{{ $step == 5 ? 'block' : 'hidden' }}">
            <label class="block text-gray-700">¿Cuál es tu objetivo?</label>
            <select name="objetivo" class="w-full px-4 py-2 border rounded-lg">
                <option value="" disabled selected>Selecciona un objetivo</option>
                <option value="perder_peso" {{ session('objetivo') == 'perder_peso' ? 'selected' : '' }}>Bajar de peso</option>
                <option value="mantener_peso" {{ session('objetivo') == 'mantener_peso' ? 'selected' : '' }}>Mantener peso</option>
                <option value="ganar_musculo" {{ session('objetivo') == 'ganar_musculo' ? 'selected' : '' }}>Subir de peso</option>
            </select>
        </div>

        <!-- Pregunta 6: Actividad física -->
        <div class="{{ $step == 6 ? 'block' : 'hidden' }}">
            <label class="block text-gray-700">¿Cuál es tu nivel de actividad física?</label>
            <select name="actividad" class="w-full px-4 py-2 border rounded-lg">
                <option value="" disabled selected>Selecciona tu nivel de actividad</option>
                <option value="sedentario" {{ session('actividad') == 'sedentario' ? 'selected' : '' }}>Sedentario</option>
                <option value="ligero" {{ session('actividad') == 'ligero' ? 'selected' : '' }}>Ligero</option>
                <option value="moderado" {{ session('actividad') == 'moderado' ? 'selected' : '' }}>Moderado</option>
                <option value="intenso" {{ session('actividad') == 'intenso' ? 'selected' : '' }}>Intenso</option>
            </select>
        </div>

        <!-- Botones de navegación -->
        <div class="mt-6 flex justify-between">
            @if ($step > 1)
                <a href="{{ route('questionnaire.show', ['step' => $step - 1]) }}" class="px-4 py-2 bg-gray-300 rounded-lg">Atrás</a>
            @endif

            <button type="submit" class="px-4 py-2 bg-[#96c464] text-white rounded-lg">
                {{ $step == 6 ? 'Finalizar' : 'Siguiente' }}
            </button>
        </div>
    </form>

</div>

</body>
</html>
