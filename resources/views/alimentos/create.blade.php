@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Agregar nuevo alimento</h1>

        <form method="POST" action="{{ route('alimentos.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Nombre:</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Categoría:</label>
                <select name="categoria" class="form-control">
                    <option value="proteinas">Proteínas</option>
                    <option value="carbohidratos">Carbohidratos</option>
                    <option value="frutas">Frutas</option>
                    <option value="verduras">Verduras</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Imagen:</label>
                <input type="file" name="imagen" class="form-control" accept="image/*">
            </div>

            <div class="mb-3">
                <label>Calorías:</label>
                <input type="number" name="calorias" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Proteínas:</label>
                <input type="number" name="proteinas" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Carbohidratos:</label>
                <input type="number" name="carbohidratos" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Grasas:</label>
                <input type="number" name="grasas" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@endsection
