@php
    $current = Route::currentRouteName();
@endphp

<nav class="fixed top-0 left-0 w-full bg-[#e5f2d8] shadow-md z-50 py-3">
    <div class="flex items-center justify-between px-6 max-w-7xl mx-auto">
        <!-- 🔥 LOGO -->
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.logo_alt') }}" class="w-10 h-10">
            <span class="text-xl font-semibold">{{ __('messages.logo_text') }}</span>
        </div>

        <a href="{{ route('home') }}"
           class="no-underline px-4 py-2 rounded-full font-medium transition duration-300
        {{ $current === 'home' ? 'bg-green-600 text-white shadow' : 'bg-white text-gray-700 hover:bg-green-100' }}">
            {{ __('messages.nav_home') }}
        </a>

        <a href="{{ route('posts.index') }}"
           class="no-underline px-4 py-2 rounded-full font-medium transition duration-300
        {{ $current === 'posts.index' ? 'bg-green-600 text-white shadow' : 'bg-white text-gray-700 hover:bg-green-100' }}">
            🥗 {{ __('messages.nav_blog') }}
        </a>

        <a href="{{ route('dashboard') }}"
           class="no-underline px-4 py-2 rounded-full font-medium transition duration-300
        {{ $current === 'dashboard' ? 'bg-green-600 text-white shadow' : 'bg-white text-gray-700 hover:bg-green-100' }}">
            👤 {{ __('messages.nav_dashboard') }}
        </a>

        <!-- Botón cambio de idioma -->
        <a href="{{ route('change.language', ['locale' => App::getLocale() === 'es' ? 'en' : 'es']) }}"
           title="{{ __('messages.change_language_tooltip') }}"
           class="ml-4 bg-white border border-gray-400 hover:bg-gray-100 rounded-full p-2 transition duration-300 shadow-sm hover:shadow-md flex items-center justify-center"
           style="width: 40px; height: 40px;">
            🌐
        </a>

        <!-- 🔥 Usuario -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="bg-[#a7d675] hover:bg-[#96c464] text-gray-800 px-6 py-2 rounded-full flex items-center gap-2 transition-all duration-300 hover:scale-105">
                <span>{{ Auth::user()->name }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 15l7-7 7 7" />
                </svg>
            </button>

            <div x-show="open" @click.away="open = false"
                 class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg py-2">
                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 text-gray-700 hover:bg-gray-100">📝 {{ __('messages.profile') }}</a>

                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.users') }}"
                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100">⚙️ {{ __('messages.admin') }}</a>
                @endif

                @if(Auth::user()->hasRole('nutricionista'))
                    <a href="{{ route('nutricionista.panel') }}"
                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100">🍏 {{ __('messages.nutritionist_panel') }}</a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-2 text-red-500 hover:bg-gray-100">🚪 {{ __('messages.logout') }}</button>
                </form>
            </div>
        </div>
    </div>
</nav>
