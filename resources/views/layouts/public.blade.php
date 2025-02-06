<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calorix - Selección de Alimentos</title>
    @vite('resources/css/app.css')
    <style>
        html, body {
            background-color: #f8fff4;
            color: #333;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }
    </style>
</head>
<body>
<main class="max-w-5xl mx-auto px-6 py-12">
    @yield('content')
</main>
</body>
</html>
