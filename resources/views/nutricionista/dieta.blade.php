@extends('layouts.CalorixLayout')

@section('content')
    <div class="container mx-auto mt-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">🍽️ Dieta de {{ $cliente->name }}</h1>

        <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-300">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">📅 Plan de Alimentación</h2>

            <table class="table-auto w-full border-collapse border border-gray-200">
                <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">Día</th>
                    <th class="border border-gray-300 px-4 py-2">Comida</th>
                    <th class="border border-gray-300 px-4 py-2">Alimento</th>
                    <th class="border border-gray-300 px-4 py-2">Cantidad</th>
                    <th class="border border-gray-300 px-4 py-2">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @php
                    // Orden correcto de los días de la semana
                    $ordenDias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

                    // Orden correcto de las comidas en el día
                    $ordenComidas = ['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'];

                    // Convertir la dieta del cliente en array y ordenarla
                    $dietaData = json_decode($cliente->dieta->dieta, true) ?? [];
                @endphp

                @foreach ($ordenDias as $dia)
                    @if(isset($dietaData[$dia]))
                        @foreach ($ordenComidas as $tipoComida)
                            @if(isset($dietaData[$dia][$tipoComida]))
                                @foreach ($dietaData[$dia][$tipoComida] as $alimento)
                                    <tr class="text-center">
                                        <td class="border border-gray-300 px-4 py-2">{{ $dia }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $tipoComida }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $alimento['nombre'] }}</td>
                                        <td class="border border-gray-300 px-4 py-2">{{ $alimento['cantidad'] }}g</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            @can('update', $dieta)
                                            <a href="{{ route('nutricionista.dieta.editar', [
                                                    'clienteId' => $cliente->id,
                                                    'dia' => $dia,
                                                    'tipoComida' => $tipoComida,
                                                    'alimentoId' => $alimento['alimento_id']
                                                ]) }}"
                                               class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded">
                                                ✏️ Editar
                                            </a>
                                            @endcan

                                                @can('delete', $dieta)

                                                <form action="{{ route('nutricionista.dieta.delete', [
            'clienteId' => $cliente->id,
            'dia' => $dia,
            'tipoComida' => $tipoComida,
            'alimentoId' => $alimento['alimento_id']
        ]) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                                                            🗑️ Eliminar
                                                        </button>
                                                    </form>
                                                @endcan

                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
                </tbody>
            </table>

            <!-- Botón para agregar alimento -->
            <div class="mt-6 text-center">
                <a href="{{ route('nutricionista.dieta.form_agregar', $cliente->id) }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    ➕ Agregar Alimento
                </a>


            </div>
        </div>
    </div>
@endsection
