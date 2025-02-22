<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Carga de CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Evitar múltiples instancias de Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

<div class="min-h-screen bg-gray-100 dark:bg-gray-900">


    <!-- Contenido de Livewire -->
    <div class="bg-[#f8fff4] min-h-screen">
        <main>

            {{ $slot }} {{-- Livewire --}}

        </main>
    </div>
</div>

<!-- Livewire Scripts -->
@livewireScripts

<!-- AOS y Re-activación para Livewire -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    import AOS from "aos/src/js/aos.js";

    document.addEventListener("DOMContentLoaded", function () {
        AOS.init({
            duration: 800,
            once: true,
        });
    });

    document.addEventListener('livewire:load', function () {
        Livewire.hook('message.processed', () => {
            AOS.refresh();
        });
    });
</script>
</body>
</html>
