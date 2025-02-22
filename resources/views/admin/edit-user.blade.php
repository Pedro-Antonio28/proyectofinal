@extends('layouts.CalorixLayout')

@section('content')
    <div class="container">
        <h1>Editar Usuario</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.users.update', $usuario->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" value="{{ $usuario->email }}" class="form-control">
            </div>

            <div class="mb-3">
                <label>Nueva Contraseña (opcional):</label>
                <input type="password" name="password" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
