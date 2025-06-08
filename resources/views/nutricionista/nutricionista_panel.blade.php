@extends('layouts.CalorixLayout')

@section('content')
    <div class="container mx-auto mt-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">👨‍⚕️ {{ __('messages.nutritionist_panel') }}</h1>

        <div class="mt-6 text-center">
            <a href="{{ route('dashboard') }}"
               class="inline-block bg-gray-600 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">
                🔙 {{ __('messages.back_to_dashboard') }}
            </a>

            <!-- Botón cambio de idioma -->
            <a href="{{ route('change.language', ['locale' => App::getLocale() === 'es' ? 'en' : 'es']) }}"
               title="{{ __('messages.change_language_tooltip') }}"
               class="ml-4 bg-white border border-gray-400 hover:bg-gray-100 rounded-full p-2 transition duration-300 shadow-sm hover:shadow-md flex items-center justify-center"
               style="width: 40px; height: 40px;">
                🌐
            </a>
        </div>

        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-300">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">👥 {{ __('messages.your_clients') }}</h2>

            <ul class="space-y-4">
                @foreach ($clientes as $cliente)
                    <li class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
                        <span class="text-gray-800 font-semibold">{{ $cliente->name }}</span>
                        <a href="{{ route('nutricionista.cliente.dieta', $cliente->id) }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                            📖 {{ __('messages.view_diet') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
