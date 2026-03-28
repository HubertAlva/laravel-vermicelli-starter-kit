<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class TestFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => 'required|string|max:255',
            'textarea' => 'required|string|max:512',
            'taglistbox' => 'required|array',
            'tags' => 'required|array|max:3',
            'tags.*' => 'string|max:255',
            'switch' => 'required|accepted',
            'select' => 'required|numeric',
            'radio' => 'required|numeric',
            'phone' => 'required|string|min:9|max:9',
            'password' => [
                'required',
                Password::default(),
            ],
            'number' => 'required|numeric|min:1|max:100',
            'markdown' => 'required|string|max:1024',
            'is_new_image' => 'boolean',
            'image' => array_merge(
                ['required'],
                request('is_new_image') ? ['image', 'mimes:jpg,jpeg,png,webp'] : [''],
                ['max:10240']
            ),
            'file' => 'required|file|max:512',
            'combobox' => 'required|numeric',
            'checkbox' => 'required|accepted',
            'date' => 'required|date',
            'datetime' => 'required|date'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
