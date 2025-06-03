<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'macros' => 'required|array',
            'macros.calories' => 'required|numeric',
            'macros.protein' => 'required|numeric',
            'macros.carbs' => 'required|numeric',
            'macros.fat' => 'required|numeric',
            'ingredients' => 'nullable|array',
            'ingredients.*.name' => 'required|string',
            'ingredients.*.quantity' => 'required|string',
            'image_path' => 'nullable|string'
        ];
    }
}
