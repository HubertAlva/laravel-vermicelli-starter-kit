<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->route('user')->id ?? null)
            ],
            'role' => 'nullable|string|in:admin',
            'password' => [
                'nullable',
                Password::default(),
                'confirmed'
            ]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
