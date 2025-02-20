<!DOCTYPE html>
<html lang="es">
<head>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Calorix</title>
    @vite('resources/css/app.css')
</head>
<body class="flex items-center justify-center min-h-screen bg-[#f8fff4]">

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="text-red-500">Cerrar sesión</button>
</form>
<div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Bienvenido a tu Dashboard</h2>

    <p>Has completado el cuestionario correctamente.</p>

    <div class="mt-4 p-4 bg-gray-100 rounded-lg">
        <h3 class="text-lg font-semibold">Tu cálculo de calorías</h3>
        <p><strong>Calorías diarias recomendadas:</strong> {{ Auth::user()->calorias_necesarias }} kcal</p>
    </div>
</div>

<div class="mt-4 p-4 bg-gray-100 rounded-lg">
    <h3 class="text-lg font-semibold">Tu cálculo de calorías y macronutrientes</h3>
    <p><strong>Calorías diarias recomendadas:</strong> {{ Auth::user()->calorias_necesarias ?? 'No calculado aún' }} kcal</p>
    <p><strong>Proteínas:</strong> {{ Auth::user()->proteinas ?? 'No calculado' }} g</p>
    <p><strong>Carbohidratos:</strong> {{ Auth::user()->carbohidratos ?? 'No calculado' }} g</p>
    <p><strong>Grasas:</strong> {{ Auth::user()->grasas ?? 'No calculado' }} g</p>
</div>

<div class="mt-4 p-4 bg-gray-100 rounded-lg">
    <h3 class="text-lg font-semibold">Tus alimentos favoritos</h3>
    <ul>
        @foreach(Auth::user()->alimentosFavoritos as $alimento)
            <li>{{ $alimento->nombre }} - {{ $alimento->calorias }} kcal</li>
        @endforeach
    </ul>
</div>


</body>


</html>
