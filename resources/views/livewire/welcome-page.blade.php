@extends('layouts.app')

<div class="min-h-screen bg-[#f8fff4]">
    <!-- Navbar fija -->
    <nav class="fixed top-0 left-0 w-full bg-[#e5f2d8] shadow-md z-50 py-5">
        <div class="flex items-center justify-between px-6 max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Calorix Logo" class="w-10 h-10">
                <span class="text-xl font-semibold">Calorix</span>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900 transition-all duration-300">Inicio</a>
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-gray-900 transition-all duration-300">Sobre Nosotros</a>
                <a href="{{ route('blog') }}" class="text-gray-700 hover:text-gray-900 transition-all duration-300">Blog de Nutrición</a>
            </div>

            <a href="{{ route('login') }}" class="bg-[#a7d675] hover:bg-[#96c464] text-gray-800 px-6 py-2 rounded-full flex items-center gap-2 transition-all duration-300 hover:scale-105">
                Iniciar Sesión
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </nav>

    <!-- Sección Principal -->
    <main class="px-6 py-12 max-w-7xl mx-auto pt-20">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Texto con animación -->
            <div class="space-y-8" data-aos="fade-right">
                <h1 class="text-5xl font-bold text-gray-900 leading-tight">
                    Bienvenido a Calorix
                </h1>
                <p class="text-xl text-gray-600">
                    Descubre cómo podemos personalizar tu dieta para mejorar tu salud y bienestar.
                </p>
                <a href="{{ route('register') }}" class="inline-block bg-[#a7d675] hover:bg-[#96c464] text-gray-800 px-8 py-3 rounded-full text-lg font-medium transition-all duration-300 hover:scale-110">
                    Empieza con tu plan personalizado
                </a>
            </div>

            <!-- Imagen con animación -->
            <div class="relative" data-aos="zoom-in">
                <img
                    src="{{ asset('images/hero-image.jpg') }}"
                    alt="Fitness y nutrición"
                    class="rounded-2xl shadow-xl w-full h-auto transition-all duration-500 hover:scale-105"
                >
            </div>
        </div>
        <section class="mt-24 max-w-7xl mx-auto" data-aos="fade-up">
            <div class="grid md:grid-cols-2 gap-12 items-center p-8 bg-[#f0f9eb] rounded-3xl">
                <!-- Columna de texto con animación -->
                <div class="space-y-6 transform transition-all duration-500 hover:translate-y-[-10px]" data-aos="fade-right">
                    <h2 class="text-4xl font-bold text-gray-900">
                        Mejora tu Bienestar
                    </h2>
                    <p class="text-lg text-gray-600">
                        Nuestros servicios únicos están diseñados para ofrecerte una nutrición óptima a través de dietas personalizadas.
                    </p>
                    <button class="bg-[#a7d675] hover:bg-[#96c464] text-gray-800 px-6 py-3 rounded-full transition-all duration-300 transform hover:scale-105">
                        Descubre más
                    </button>
                </div>

                <!-- Imagen con sombra y efecto zoom -->
                <div class="relative" data-aos="zoom-in" data-aos-delay="200">
                    <div class="overflow-hidden rounded-2xl shadow-lg transform transition-all duration-500 hover:scale-105">
                        <img
                            src="{{ asset('images/frutas.jpg') }}"
                            alt="Variedad de frutas frescas"
                            class="max-w-90 h-90 object-cover"
                        >
                    </div>

                    <!-- Tarjeta de estadísticas con animación de entrada -->
                    <div class="absolute bottom-[-25px] left-[-47px] bg-[#d3eab8] p-8 rounded-2xl w-[400px] shadow-lg transform transition-all duration-500 hover:translate-y-[-5px]" data-aos="fade-up" data-aos-delay="400">
                        <div class="flex items-center justify-center mb-3">
                            <svg class="w-12 h-12 text-[#7ab940]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <div class="text-5xl font-bold text-gray-900 mb-2">1,500</div>
                            <div class="text-gray-600 text-lg">Clientes satisfechos este mes</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 max-w-7xl mx-auto px-6 flex flex-col items-center text-center" data-aos="fade-up">
            <!-- Encabezado con animación -->
            <div class="space-y-6 max-w-4xl mb-16" data-aos="fade-down">
                <h3 class="text-[#a7d675] text-xl font-medium">Ventajas de Calorix</h3>
                <h2 class="text-4xl font-bold text-gray-900">Explora los Beneficios</h2>
                <p class="text-2xl text-gray-600 leading-relaxed">
                    Nuestras dietas personalizadas se adaptan a tus necesidades específicas, promoviendo un estilo de vida saludable.
                </p>
            </div>

            <!-- Tarjetas de beneficios con animaciones escalonadas -->
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <!-- Salud Óptima -->
                <div class="group space-y-6 p-6 rounded-2xl transition-all duration-300 hover:bg-white hover:shadow-xl transform hover:scale-105 flex flex-col items-center"
                     data-aos="fade-right" data-aos-delay="100">
                    <div class="w-16 h-16 rounded-2xl bg-[#f0f9eb] flex items-center justify-center group-hover:bg-[#e8f5d5] transition-colors duration-300">
                        <svg class="w-8 h-8 text-[#a7d675]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Salud Óptima</h3>
                    <p class="text-gray-600">
                        Diseñamos planes que te ayudan a alcanzar tus objetivos de salud.
                    </p>
                </div>

                <!-- Apoyo Constante -->
                <div class="group space-y-6 p-6 rounded-2xl transition-all duration-300 hover:bg-white hover:shadow-xl transform hover:scale-105 flex flex-col items-center"
                     data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 rounded-2xl bg-[#f0f9eb] flex items-center justify-center group-hover:bg-[#e8f5d5] transition-colors duration-300">
                        <svg class="w-8 h-8 text-[#a7d675]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Apoyo Constante</h3>
                    <p class="text-gray-600">
                        Nuestro equipo de expertos está aquí para guiarte en cada paso.
                    </p>
                </div>

                <!-- Resultados Reales -->
                <div class="group space-y-6 p-6 rounded-2xl transition-all duration-300 hover:bg-white hover:shadow-xl transform hover:scale-105 flex flex-col items-center"
                     data-aos="fade-left" data-aos-delay="300">
                    <div class="w-16 h-16 rounded-2xl bg-[#f0f9eb] flex items-center justify-center group-hover:bg-[#e8f5d5] transition-colors duration-300">
                        <svg class="w-8 h-8 text-[#a7d675]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Resultados Reales</h3>
                    <p class="text-gray-600">
                        Transforma tu vida con resultados visibles y sostenibles.
                    </p>
                </div>
            </div>

            <!-- Botón con efecto de elevación -->
            <div class="mt-12" data-aos="fade-up" data-aos-delay="400">
                <a href="#" class="inline-flex items-center justify-center px-6 py-3 bg-[#a7d675] hover:bg-[#96c464] text-gray-800 rounded-full transition-all duration-300 transform hover:scale-105 hover:translate-y-[-3px]">
                    Empezar con el plan
                </a>
            </div>
        </section>

    </main>


    <!-- Footer -->
    <footer class="mt-24 border-t border-gray-200 bg-[#f8fff4] transition-all duration-500 hover:translate-y-[-2px]" data-aos="fade-up">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <!-- Enlaces de redes sociales con animaciones -->
                <div class="flex items-center gap-4">
                    <a href="#" class="text-gray-600 hover:text-[#7ab940] transition-colors duration-300 transform hover:scale-110 hover:rotate-6">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-[#7ab940] transition-colors duration-300 transform hover:scale-110 hover:rotate-6">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-[#7ab940] transition-colors duration-300 transform hover:scale-110 hover:rotate-6">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63z"/>
                        </svg>
                    </a>
                </div>

                <!-- Texto de copyright con animación -->
                <p class="text-gray-600 text-center" data-aos="fade-up" data-aos-delay="200">
                    © Calorix 2025. Todos los derechos reservados. Promoviendo una nutrición saludable para todos.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            AOS.init({
                duration: 800,
                once: true,
            });
        });
    </script>
</div>
