<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.login') }} - Calorix</title>
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
        <h2 class="text-3xl font-bold text-gray-900 mt-4">{{ __('messages.login') }}</h2>
        <p class="text-gray-600 mt-2">{{ __('messages.login_subtitle') }}</p>
    </div>

    <div class="flex justify-end mb-2">
        @include('components.language-switcher')
    </div>
    <!-- Formulario de inicio de sesión -->
    <form action="/login" method="POST">
        @csrf

        <!-- Campo Email -->
        <div>
            <label for="email" class="block text-gray-700 font-medium">{{ __('messages.email') }}</label>
            <input type="email" id="email" name="email" class="mt-2 w-full px-4 py-3 border rounded-lg focus:ring focus:ring-[#a7d675] focus:outline-none" placeholder="{{ __('messages.email_placeholder') }}" required>
        </div>

        <!-- Campo Contraseña -->
        <div class="mt-4">
            <label for="password" class="block text-gray-700 font-medium">{{ __('messages.password') }}</label>
            <input type="password" id="password" name="password" class="mt-2 w-full px-4 py-3 border rounded-lg focus:ring focus:ring-[#a7d675] focus:outline-none" placeholder="••••••••" required>
        </div>

        <!-- Botón de inicio de sesión -->
        <button type="submit" class="mt-6 w-full bg-[#a7d675] hover:bg-[#96c464] text-white py-3 rounded-lg font-medium transition-all duration-300 hover:scale-105">
            {{ __('messages.login') }}
        </button>
    </form>

    <!-- Registro -->
    <p class="text-center text-gray-600 mt-6">
        {{ __('messages.no_account') }}
        <a href="{{ route('register') }}" class="text-[#a7d675] hover:underline">{{ __('messages.register_here') }}</a>
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
