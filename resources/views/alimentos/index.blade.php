@extends('layouts.CalorixLayout')

@section('content')
    <div class="container">
        <h1 class="mb-4">Lista de Alimentos</h1>
        <a href="{{ route('alimentos.create') }}" class="btn btn-primary mb-3">Agregar Alimento</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Calorías</th>
                <th>Proteínas</th>
                <th>Carbohidratos</th>
                <th>Grasas</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($alimentos as $alimento)
                <tr>
                    <td>{{ $alimento->nombre }}</td>
                    <td>{{ $alimento->calorias }} kcal</td>
                    <td>{{ $alimento->proteinas }} g</td>
                    <td>{{ $alimento->carbohidratos }} g</td>
                    <td>{{ $alimento->grasas }} g</td>
                    <td>
                        <a href="{{ route('alimentos.edit', $alimento->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('alimentos.destroy', $alimento->id) }}" method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
