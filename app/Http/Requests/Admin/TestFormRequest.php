<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class TestFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => ['required', 'string', 'max:255'],
            'textarea' => ['required', 'string', 'max:512'],
            'taglistbox' => ['required', 'array'],
            'tags' => ['required', 'array', 'max:3'],
            'tags.*' => ['string', 'max:255'],
            'switch' => ['accepted'],
            'select' => ['required', 'integer'],
            'radio' => ['required', 'integer'],
            'phone' => ['required', 'digits:9'],
            'password' => ['required', 'string', Password::default()],
            'number' => ['required', 'integer', 'min:1', 'max:100'],
            'markdown' => ['required', 'string', 'max:1024'],
            'is_new_image' => ['boolean'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'file' => ['required', 'file', 'max:512'],
            'combobox' => ['required', 'integer'],
            'checkbox' => ['accepted'],
            'date' => ['required', 'date_format:Y-m-d'],
            'datetime' => ['required', 'date'],
        ];
    }
}
