<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDietaAlimentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // puedes ajustar si necesitas verificar rol admin
    }

    public function rules(): array
    {
        return [
            'cantidad' => 'required|numeric|min:1',
        ];
    }
}
