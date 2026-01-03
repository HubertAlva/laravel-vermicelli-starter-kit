<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'required|boolean',
            'is_new_thumbnail' => 'boolean',
            'thumbnail' => array_merge(
                ['nullable'],
                request('is_new_thumbnail') ? ['image', 'mimes:jpg,jpeg,png,webp'] : [''],
                ['max:10240']
            ),
            'tags' => 'array',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
