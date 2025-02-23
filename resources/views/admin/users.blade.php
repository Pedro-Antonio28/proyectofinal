@extends('layouts.CalorixLayout')

@section('content')
    <div class="container">
        <h1 class="mb-4">Administración de Usuarios</h1>
        <!-- Botón de volver al Dashboard -->
        <div class="d-flex justify-content-end mb-4">
            <!-- Botón de volver al Dashboard alineado a la derecha -->
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                ⬅ Volver al Dashboard
            </a>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Contraseña</th>
                <th>Dieta</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>
                        <input type="password" value="{{ $usuario->password }}" class="form-control password-input" readonly>
                        <button class="btn btn-sm btn-secondary toggle-password">👁</button>
                    </td>
                    <td>
                        @if($usuario->dieta)
                            <a href="{{ route('admin.users.dieta', $usuario->id) }}" class="btn btn-info btn-sm">
                                🍽️ Ver Dieta
                            </a>
                        @else
                            <span class="text-muted">No asignada</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.users.edit', $usuario->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        @can('deleteUser', $usuario)
                            <form action="{{ route('admin.users.delete', $usuario->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">
                                    Eliminar
                                </button>
                            </form>
                        @endcan

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                let input = this.previousElementSibling;
                if (input.type === 'password') {
                    input.type = 'text';
                } else {
                    input.type = 'password';
                }
            });
        });
    </script>

@endsection
