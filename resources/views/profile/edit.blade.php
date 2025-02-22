@extends('layouts.CalorixLayout')

@section('content')
    <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-8 border border-gray-300 mt-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">👤 Editar Perfil</h2>

        <!-- Sección de Perfil -->
        <div class="flex flex-col md:flex-row items-center gap-6">
            <!-- Imagen de perfil (Placeholder) -->
            <div class="flex-shrink-0">
                <img src="{{ asset('images/user-placeholder.png') }}" alt="Foto de perfil"
                     class="w-28 h-28 rounded-full shadow-md border border-gray-300">
            </div>

            <!-- Información del Usuario -->
            <div class="flex-grow text-gray-700 space-y-2 w-full">
                <div class="bg-gray-100 p-4 rounded-lg">
                    <p><strong>📧 Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>📅 Miembro desde:</strong> {{ Auth::user()->created_at->format('d M Y') }}</p>
                    <p><strong>🎯 Rol:</strong>
                        <span class="bg-green-500 text-white px-3 py-1 rounded-md">
                        {{ ucfirst(Auth::user()->roles->first()->name ?? 'Usuario') }}
                    </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulario de edición -->
        <form method="POST" action="{{ route('profile.update') }}" class="mt-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Nombre</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Email (No editable) -->
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Correo Electrónico</label>
                    <input type="email" value="{{ Auth::user()->email }}" disabled
                           class="w-full px-4 py-2 border border-gray-300 bg-gray-200 rounded-lg cursor-not-allowed">
                </div>
            </div>

            <!-- Contraseña -->
            <div class="mt-4">
                <label class="block text-gray-700 font-semibold mb-1">Nueva Contraseña (Opcional)</label>
                <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mt-4">
                <label class="block text-gray-700 font-semibold mb-1">Confirmar Nueva Contraseña</label>
                <input type="password" name="password_confirmation"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Botones -->
            <div class="mt-6 flex justify-between">
                <a href="{{ route('dashboard') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                    ⬅ Volver
                </a>

                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                    💾 Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@endsection
