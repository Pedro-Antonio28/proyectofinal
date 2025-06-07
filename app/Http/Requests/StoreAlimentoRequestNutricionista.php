<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // O puedes verificar si es nutricionista
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'calorias' => 'required|numeric|min:0',
            'proteinas' => 'required|numeric|min:0',
            'carbohidratos' => 'required|numeric|min:0',
            'grasas' => 'required|numeric|min:0',
            'cantidad' => 'required|numeric|min:1',
            'dia' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'tipo_comida' => 'required|in:Desayuno,Almuerzo,Comida,Merienda,Cena',
        ];
    }
}
