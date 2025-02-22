<!-- 📌 Barra de Navegación (Navbar) -->
<nav class="fixed top-0 left-0 w-full bg-[#e5f2d8] shadow-md z-50 py-3">

    <div class="flex items-center justify-between px-6 max-w-7xl mx-auto">
        <!-- 🔥 LOGO -->
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="Calorix Logo" class="w-10 h-10">
            <span class="text-xl font-semibold">Calorix</span>
        </div>

        <!-- 🔥 Menú de Navegación -->
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-gray-900 transition-all duration-300">Inicio</a>
            <a href="{{ route('about') }}" class="text-gray-700 hover:text-gray-900 transition-all duration-300">Sobre Nosotros</a>
            <a href="{{ route('blog') }}" class="text-gray-700 hover:text-gray-900 transition-all duration-300">Blog de Nutrición</a>
        </div>

        <!-- 🔥 MENÚ DE CUENTA DEL USUARIO -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="bg-[#a7d675] hover:bg-[#96c464] text-gray-800 px-6 py-2 rounded-full flex items-center gap-2 transition-all duration-300 hover:scale-105">
                <span>{{ Auth::user()->name }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>

            <!-- 🔥 Dropdown de usuario -->
            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg py-2">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    📝 Ver Perfil
                </a>


                <!-- 🔥 Solo visible para administradores -->
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        ⚙️ Panel de Administración
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-500 hover:bg-gray-100">
                        🚪 Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
