@extends('layouts.CalorixLayout')

@section('content')
    <div class="container mx-auto mt-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">👨‍⚕️ Panel de Nutricionista</h1>

        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-300">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">👥 Tus Clientes</h2>

            <ul class="space-y-4">
                @foreach ($clientes as $cliente)
                    <li class="flex justify-between items-center bg-gray-100 p-4 rounded-lg shadow-md">
                        <span class="text-gray-800 font-semibold">{{ $cliente->name }}</span>
                        <a href="{{ route('nutricionista.cliente.dieta', $cliente->id) }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all duration-300">
                            📖 Ver Dieta
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
