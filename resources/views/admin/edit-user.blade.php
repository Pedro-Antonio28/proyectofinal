@extends('layouts.CalorixLayout')

@section('content')
    <div class="container">
        <h1>{{ __('messages.edit_user') }}</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Formulario para actualizar el email --}}
        <form action="{{ route('admin.users.update.email', $usuario->id) }}" method="POST" class="mb-4">
            @csrf
            <div class="mb-3">
                <label>{{ __('messages.email') }}:</label>
                <input type="email" name="email" value="{{ $usuario->email }}" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('messages.update_email') }}</button>
        </form>

        {{-- Formulario para actualizar la contraseña --}}
        <form action="{{ route('admin.users.update.password', $usuario->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>{{ __('messages.new_password') }}:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning">{{ __('messages.update_password') }}</button>
        </form>

        <a href="{{ route('admin.users') }}" class="btn btn-secondary mt-3">{{ __('messages.cancel') }}</a>
    </div>
@endsection
