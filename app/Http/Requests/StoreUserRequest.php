<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|lowercase|email|max:255|unique:users,email',
            'role' => 'nullable|string|in:admin',
            'password' => [
                'required',
                Password::default(),
                'confirmed',
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
