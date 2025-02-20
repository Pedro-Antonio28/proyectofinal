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

    <!-- Asegurar que solo haya UNA instancia de Alpine.js -->

    <style>
        html {
            scroll-behavior: smooth;
        }
        .bg-gray-900 {
            background-color: #f8fff4 !important;
        }

    </style>
</head>
<body class="min-h-screen bg-[#f8fff4]">
<script>
    document.addEventListener("DOMContentLoaded", function () {
        AOS.init({
            duration: 800,
            once: true,
        });
    });
</script>
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    @include('layouts.navigation')

    <!-- Contenido de Livewire -->
    <div class="bg-[#f8fff4] min-h-screen">
    <main>

            {{ $slot }} {{-- Livewire --}}

    </main>
    </div>

</div>

<!-- Livewire Scripts -->
@livewireScripts

<!-- Asegurar que Alpine.js se carga después de Livewire -->

</body>
</html>
