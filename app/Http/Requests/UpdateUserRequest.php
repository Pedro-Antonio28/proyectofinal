<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email,' . $this->route('id'),
            'password' => 'nullable|string|min:6|confirmed',
        ];
    }
}
