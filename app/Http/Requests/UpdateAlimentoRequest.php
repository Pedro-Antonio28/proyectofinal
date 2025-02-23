<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
}
