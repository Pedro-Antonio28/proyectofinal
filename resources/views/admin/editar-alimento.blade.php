@extends('layouts.CalorixLayout')

@section('content')
    <div class="container">
        <h1>✏️ {{ __('messages.edit_food') }}</h1>

        <a href="{{ route('admin.users.dieta', $alimento->dieta->user_id) }}" class="btn btn-secondary mb-3">⬅ {{ __('messages.back') }}</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.dieta.update-alimento', $alimento->id) }}" method="POST">
            @csrf
            @method('POST')

            <div class="mb-3">
                <label class="form-label">{{ __('messages.food_name') }}:</label>
                <input type="text" value="{{ $alimento->alimento->nombre }}" class="form-control" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('messages.amount_g') }}:</label>
                <input type="number" name="cantidad" value="{{ old('cantidad', $alimento->cantidad) }}" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>
        </form>
    </div>
@endsection
