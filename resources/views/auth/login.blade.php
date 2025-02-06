<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Calorix</title>
    @vite('resources/css/app.css')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
        .bg-gradient {
            background: linear-gradient(135deg, #e5f2d8, #c1e1a6);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient">

<!-- Contenedor principal -->
<div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8" data-aos="fade-up">

    <!-- Logo y título -->
    <div class="text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Calorix Logo" class="w-16 h-16 mx-auto">
        <h2 class="text-3xl font-bold text-gray-900 mt-4">Iniciar Sesión</h2>
        <p class="text-gray-600 mt-2">Accede a tu cuenta para continuar</p>
    </div>

    <!-- Formulario de inicio de sesión -->
    <form action="/login" method="POST">

    @csrf


        <!-- Campo Email -->
        <div>
            <label for="email" class="block text-gray-700 font-medium">Correo Electrónico</label>
            <input type="email" id="email" name="email" class="mt-2 w-full px-4 py-3 border rounded-lg focus:ring focus:ring-[#a7d675] focus:outline-none" placeholder="correo@ejemplo.com" required>
        </div>

        <!-- Campo Contraseña -->
        <div class="mt-4">
            <label for="password" class="block text-gray-700 font-medium">Contraseña</label>
            <input type="password" id="password" name="password" class="mt-2 w-full px-4 py-3 border rounded-lg focus:ring focus:ring-[#a7d675] focus:outline-none" placeholder="••••••••" required>
        </div>

        <!-- Recordar sesión y contraseña olvidada -->
        <div class="flex items-center justify-between mt-4">
            <label class="flex items-center text-gray-600">
                <input type="checkbox" name="remember" class="mr-2">
                Recordarme
            </label>
            <a href="#" class="text-[#a7d675] hover:underline">¿Olvidaste tu contraseña?</a>
        </div>

        <!-- Botón de inicio de sesión -->
        <button type="submit" class="mt-6 w-full bg-[#a7d675] hover:bg-[#96c464] text-white py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
            Iniciar Sesión
        </button>
    </form>

    <!-- Separador -->
    <div class="flex items-center justify-center my-6">
        <span class="w-1/3 border-b border-gray-300"></span>
        <span class="mx-4 text-gray-500">o</span>
        <span class="w-1/3 border-b border-gray-300"></span>
    </div>

    <!-- Botón de inicio con Google -->
    <button class="w-full flex items-center justify-center border border-gray-300 rounded-lg py-3 text-gray-700 font-medium transition-all duration-300 hover:bg-gray-100">
        <img src="{{ asset('images/logo google.png') }}" alt="Google Logo" class="w-5 h-5 mr-2">
        Iniciar sesión con Google
    </button>

    <!-- Registro -->
    <p class="text-center text-gray-600 mt-6">
        ¿No tienes una cuenta?
        <a href="{{ route('register') }}" class="text-[#a7d675] hover:underline">Regístrate aquí</a>
    </p>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        AOS.init({
            duration: 800,
            once: true,
        });
    });
</script>

</body>
</html>

