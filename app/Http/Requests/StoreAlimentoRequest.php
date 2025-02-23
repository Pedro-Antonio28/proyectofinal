<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlimentoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta petición.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define las reglas de validación para el formulario.
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'calorias' => 'required|integer|min:0',
            'proteinas' => 'required|integer|min:0',
            'carbohidratos' => 'required|integer|min:0',
            'grasas' => 'required|integer|min:0',
        ];
    }

    /**
     * Define los mensajes de error personalizados.
     */
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre del alimento es obligatorio.',
            'categoria.required' => 'La categoría del alimento es obligatoria.',
            'calorias.required' => 'Las calorías son obligatorias.',
            'proteinas.required' => 'Las proteínas son obligatorias.',
            'carbohidratos.required' => 'Los carbohidratos son obligatorios.',
            'grasas.required' => 'Las grasas son obligatorias.',
            'imagen.image' => 'El archivo debe ser una imagen válida.',
            'imagen.mimes' => 'Solo se permiten imágenes en formato jpeg, png, jpg o gif.',
            'imagen.max' => 'El tamaño de la imagen no debe superar los 2MB.',
        ];
    }
}
