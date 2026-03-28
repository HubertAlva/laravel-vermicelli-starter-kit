<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('posts')->ignore($this->route('post')->id ?? null),
            ],
            'excerpt' => 'required|string|max:512',
            'content' => 'required|string',
            'published_at' => 'boolean',
            'is_new_thumbnail' => 'boolean',
            'thumbnail' => array_merge(
                ['nullable'],
                request('is_new_thumbnail') ? ['image', 'mimes:jpg,jpeg,png,webp'] : [''],
                ['max:10240']
            ),
            'tags' => 'sometimes|array',
            'tags.*' => 'string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
