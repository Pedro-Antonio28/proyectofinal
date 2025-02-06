@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar alimento</h1>

        <form method="POST" action="{{ route('alimentos.update', $alimento->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="{{ $alimento->nombre }}" required>
            </div>

            <div class="mb-3">
                <label>Categoría:</label>
                <select name="categoria" class="form-control">
                    <option value="proteinas" {{ $alimento->categoria == 'proteinas' ? 'selected' : '' }}>Proteínas</option>
                    <option value="carbohidratos" {{ $alimento->categoria == 'carbohidratos' ? 'selected' : '' }}>Carbohidratos</option>
                    <option value="frutas" {{ $alimento->categoria == 'frutas' ? 'selected' : '' }}>Frutas</option>
                    <option value="verduras" {{ $alimento->categoria == 'verduras' ? 'selected' : '' }}>Verduras</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Imagen actual:</label><br>
                @if($alimento->imagen)
                    <img src="{{ asset('storage/' . $alimento->imagen) }}" width="100"><br>
                @endif
                <label>Cambiar imagen:</label>
                <input type="file" name="imagen" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label>Calorías:</label>
                <input type="number" name="calorias" class="form-control" value="{{ $alimento->calorias }}" required>
            </div>

            <div class="mb-3">
                <label>Proteínas:</label>
                <input type="number" name="proteinas" class="form-control" value="{{ $alimento->proteinas }}" required>
            </div>

            <div class="mb-3">
                <label>Carbohidratos:</label>
                <input type="number" name="carbohidratos" class="form-control" value="{{ $alimento->carbohidratos }}" required>
            </div>

            <div class="mb-3">
                <label>Grasas:</label>
                <input type="number" name="grasas" class="form-control" value="{{ $alimento->grasas }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>
    </div>
@endsection
