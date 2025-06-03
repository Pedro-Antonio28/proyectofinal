<div> <!-- 📌 Este div raíz es necesario para que Livewire no marque error -->
    <div class="min-h-screen bg-[#f8fff4]">
        <!-- Navbar fija -->
        <nav class="fixed top-0 left-0 w-full bg-[#e5f2d8] shadow-md z-50 py-5">
            <div class="flex items-center justify-between px-6 max-w-7xl mx-auto">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Calorix Logo" class="w-10 h-10">
                    <span class="text-xl font-semibold">Calorix</span>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900 transition-all duration-300">
                        {{ __('messages.home') }}
                    </a>

                </div>

                <a href="{{ route('change.language', ['locale' => App::getLocale() === 'es' ? 'en' : 'es']) }}"
                   class="text-gray-700 hover:text-gray-900 transition-all duration-300 px-4 py-2 rounded-full border border-gray-400">
                    {{ __('messages.change_language') }}
                </a>

                <a href="{{ route('login') }}"
                   class="bg-[#a7d675] hover:bg-[#96c464] text-gray-800 px-6 py-2 rounded-full flex items-center gap-2 transition-all duration-300 hover:scale-105">
                    {{ __('messages.login') }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
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
                        {{ __('messages.welcome') }}
                    </h1>
                    <p class="text-xl text-gray-600">
                        {{ __('messages.description') }}
                    </p>
                    <a href="{{ route('register') }}"
                       class="inline-block bg-[#a7d675] hover:bg-[#96c464] text-gray-800 px-8 py-3 rounded-full text-lg font-medium transition-all duration-300 hover:scale-110">
                        {{ __('messages.start_plan') }}
                    </a>
                </div>

                <!-- Imagen con animación -->
                <div class="relative" data-aos="zoom-in">
                    <img
                        src="{{ asset('images/hero-image.jpg') }}"
                        alt="{{ __('messages.fitness_nutrition') }}"
                        class="rounded-2xl shadow-xl w-full h-auto transition-all duration-500 hover:scale-105"
                    >
                </div>
            </div>
        </main>

        <!-- Sección de bienestar -->
        <section class="mt-24 max-w-7xl mx-auto" data-aos="fade-up">
            <div class="grid md:grid-cols-2 gap-12 items-center p-8 bg-[#f0f9eb] rounded-3xl">
                <div class="space-y-6 transform transition-all duration-500 hover:translate-y-[-10px]"
                     data-aos="fade-right">
                    <h2 class="text-4xl font-bold text-gray-900">
                        {{ __('messages.improve_health') }}
                    </h2>
                    <p class="text-lg text-gray-600">
                        {{ __('messages.custom_nutrition') }}
                    </p>
                    <button
                        class="bg-[#a7d675] hover:bg-[#96c464] text-gray-800 px-6 py-3 rounded-full transition-all duration-300 transform hover:scale-105">
                        {{ __('messages.learn_more') }}
                    </button>
                </div>
                <div class="relative" data-aos="zoom-in" data-aos-delay="200">
                    <div
                        class="overflow-hidden rounded-2xl shadow-lg transform transition-all duration-500 hover:scale-105">
                        <img
                            src="{{ asset('images/frutas.jpg') }}"
                            alt="{{ __('messages.fresh_fruits') }}"
                            class="max-w-90 h-90 object-cover"
                        >
                    </div>
                </div>
            </div>
        </section>


        <section class="py-24 max-w-7xl mx-auto px-6 flex flex-col items-center text-center" data-aos="fade-up">
            <!-- Encabezado con animación -->
            <div class="space-y-6 max-w-4xl mb-16" data-aos="fade-down">
                <h3 class="text-[#a7d675] text-xl font-medium">{{ __('messages.calorix_advantages') }}</h3>
                <h2 class="text-4xl font-bold text-gray-900">{{ __('messages.explore_benefits') }}</h2>
                <p class="text-2xl text-gray-600 leading-relaxed">
                    {{ __('messages.personalized_diets') }}
                </p>
            </div>

            <!-- Tarjetas de beneficios con animaciones escalonadas -->
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <!-- Salud Óptima -->
                <div class="group space-y-6 p-6 rounded-2xl transition-all duration-300 hover:bg-white hover:shadow-xl transform hover:scale-105 flex flex-col items-center"
                     data-aos="fade-right" data-aos-delay="100">
                    <div class="w-16 h-16 rounded-2xl bg-[#f0f9eb] flex items-center justify-center group-hover:bg-[#e8f5d5] transition-colors duration-300">
                        <svg class="w-8 h-8 text-[#a7d675]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('messages.optimal_health') }}</h3>
                    <p class="text-gray-600">
                        {{ __('messages.health_goals') }}
                    </p>
                </div>

                <!-- Apoyo Constante -->
                <div class="group space-y-6 p-6 rounded-2xl transition-all duration-300 hover:bg-white hover:shadow-xl transform hover:scale-105 flex flex-col items-center"
                     data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 rounded-2xl bg-[#f0f9eb] flex items-center justify-center group-hover:bg-[#e8f5d5] transition-colors duration-300">
                        <svg class="w-8 h-8 text-[#a7d675]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('messages.constant_support') }}</h3>
                    <p class="text-gray-600">
                        {{ __('messages.expert_guidance') }}
                    </p>
                </div>

                <!-- Resultados Reales -->
                <div class="group space-y-6 p-6 rounded-2xl transition-all duration-300 hover:bg-white hover:shadow-xl transform hover:scale-105 flex flex-col items-center"
                     data-aos="fade-left" data-aos-delay="300">
                    <div class="w-16 h-16 rounded-2xl bg-[#f0f9eb] flex items-center justify-center group-hover:bg-[#e8f5d5] transition-colors duration-300">
                        <svg class="w-8 h-8 text-[#a7d675]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ __('messages.real_results') }}</h3>
                    <p class="text-gray-600">
                        {{ __('messages.visible_transformation') }}
                    </p>
                </div>
            </div>

            <!-- Botón con efecto de elevación -->
            <div class="mt-12" data-aos="fade-up" data-aos-delay="400">
                <a href="#" class="inline-flex items-center justify-center px-6 py-3 bg-[#a7d675] hover:bg-[#96c464] text-gray-800 rounded-full transition-all duration-300 transform hover:scale-105 hover:translate-y-[-3px]">
                    {{ __('messages.start_plan') }}
                </a>
            </div>

            <!-- 🌟 Plan Premium Destacado -->
            <section class="bg-[#fef9e7] py-16 mt-24">
                <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-10">
                    <div class="flex-1 space-y-6" data-aos="fade-right">
                        <h2 class="text-4xl font-bold text-gray-900">🌟 Plan Premium Calorix</h2>
                        <p class="text-lg text-gray-700">
                            Desbloquea todas las funciones: genera dietas personalizadas ilimitadas, recibe consejos exclusivos y mejora tu salud de forma profesional.
                        </p>
                        <a href="{{ route('paypal.create') }}"
                           class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-3 rounded-full text-lg font-semibold transition-all duration-300 shadow-md hover:scale-105">
                            💳 Acceder al plan premium
                        </a>
                    </div>

                    <div class="flex-1" data-aos="zoom-in">
                        <img src="{{ asset('images/premium-plan.png') }}" alt="Plan Premium" class="w-full rounded-xl shadow-lg">
                    </div>
                </div>
            </section>

        </section>

        <!-- Footer -->
        <footer
            class="mt-24 border-t border-gray-200 bg-[#f8fff4] transition-all duration-500 hover:translate-y-[-2px]"
            data-aos="fade-up">
            <div class="max-w-7xl mx-auto px-6 py-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-4">
                        <a href="#"
                           class="text-gray-600 hover:text-[#7ab940] transition-colors duration-300 transform hover:scale-110 hover:rotate-6">
                            <span class="sr-only">{{ __('messages.twitter') }}</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </a>
                    </div>
                    <p class="text-gray-600 text-center" data-aos="fade-up" data-aos-delay="200">
                        {{ __('messages.footer_rights') }}
                    </p>
                </div>
            </div>


        </footer>


        <!-- Scripts -->
            <style>
                a, h1, h2, h3, h4, h5, h6, p, span {
                    text-decoration: none !important;
                }
            </style>
        </div> <!-- 📌 Cierre del div raíz -->
    </div> <!-- 📌 Cierre del contenedor Livewire -->

