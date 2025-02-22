@extends('layouts.CalorixLayout')

@section('content')

    @livewireStyles
    @livewireScripts

    <div class="container">
        <h1>Dieta de {{ $usuario->name }}</h1>

        <a href="{{ route('admin.users') }}" class="btn btn-secondary mb-3">⬅ Volver</a>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @php
            // Definir el orden de los días y comidas
            $ordenDias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
            $ordenComidas = ['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'];

            // Convertir el JSON de la dieta en un array
            $dietaData = json_decode($usuario->dieta->dieta, true);
        @endphp

        @foreach($ordenDias as $dia)
            @if(isset($dietaData[$dia]))
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">{{ $dia }}</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Tipo de Comida</th>
                                <th>Alimento</th>
                                <th>Cantidad</th>
                                <th>Consumido</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ordenComidas as $tipoComida)
                                @if(isset($dietaData[$dia][$tipoComida]))
                                    @foreach($dietaData[$dia][$tipoComida] as $alimento)
                                        <tr>
                                            <td>{{ $tipoComida }}</td>
                                            <td>{{ $alimento['nombre'] }}</td>
                                            <td>{{ isset($alimento['cantidad']) ? $alimento['cantidad'] : 'No disponible' }}g</td>


                                            <td>
                                                @if(isset($alimento['consumido']) && $alimento['consumido'])
                                                    <span class="badge bg-success">Sí</span>
                                                @else
                                                    <span class="badge bg-warning">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.dieta.editar-alimento', $alimento['alimento_id']) }}" class="btn btn-warning btn-sm">
                                                    ✏️ Editar
                                                </a>
                                            </td>


                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @endforeach

        <!-- Botón para eliminar la dieta completa -->
        <form action="{{ route('admin.dieta.delete', $usuario->dieta->id) }}" method="POST" class="text-center mt-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar la dieta? Esta acción no se puede deshacer.')">
                🗑️ Eliminar Dieta
            </button>
        </form>

    </div>
@endsection
