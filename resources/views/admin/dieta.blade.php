@extends('layouts.CalorixLayout')

@section('content')

    @livewireStyles
    @livewireScripts

    <div class="container">
        <h1>{{ __('messages.diet_of', ['name' => $usuario->name]) }}</h1>

        <a href="{{ route('admin.users') }}" class="btn btn-secondary mb-3">⬅ {{ __('messages.back') }}</a>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @php
            $ordenDias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
            $ordenComidas = ['Desayuno', 'Almuerzo', 'Comida', 'Merienda', 'Cena'];
            $alimentos = $usuario->dieta->alimentos->groupBy(['dia', 'tipo_comida']);
        @endphp

        @foreach($ordenDias as $dia)
            @if(isset($alimentos[$dia]))
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">{{ __('messages.day_name.' . strtolower($dia)) }}</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>{{ __('messages.meal_type') }}</th>
                                <th>{{ __('messages.food') }}</th>
                                <th>{{ __('messages.amount') }}</th>
                                <th>{{ __('messages.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($ordenComidas as $tipoComida)
                                @if(isset($alimentos[$dia][$tipoComida]))
                                    @foreach($alimentos[$dia][$tipoComida] as $alimento)
                                        <tr>
                                            <td>{{ __('messages.meal_name.' . strtolower($tipoComida)) }}</td>
                                            <td>{{ $alimento->alimento->nombre }}</td>
                                            <td>{{ $alimento->cantidad }}g</td>
                                            <td>
                                                <a href="{{ route('admin.dieta.editar-alimento', $alimento->id) }}" class="btn btn-warning btn-sm">
                                                    ✏️ {{ __('messages.edit') }}
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
            <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('messages.confirm_delete_diet') }}')">
                🗑️ {{ __('messages.delete_diet') }}
            </button>
        </form>

    </div>
@endsection
