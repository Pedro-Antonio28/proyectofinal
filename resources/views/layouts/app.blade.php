<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Carga de CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- Asegurar que Vite está configurado -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Livewire Styles -->
    @livewireStyles

    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f8fff4]">

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    @include('layouts.navigation')

    <!-- Contenido de Livewire -->
    <main>
        {{ $slot ?? '' }} {{-- Livewire usa $slot en lugar de @yield('content') --}}
    </main>
</div>

<!-- Livewire Scripts -->
@livewireScripts
</body>
</html>
