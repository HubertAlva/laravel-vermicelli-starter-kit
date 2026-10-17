<?php

namespace App\Http\Requests\Admin;

use App\Models\Post;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    /**
     * Authorize here (not only in the controller) so precognitive
     * validation requests are also restricted to allowed users.
     */
    public function authorize(): bool
    {
        $post = $this->route('post');

        return $post instanceof Post
            ? $this->user()->can('update', $post)
            : $this->user()->can('create', Post::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $post = $this->route('post');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Post::class)->ignore($post instanceof Post ? $post->id : null),
            ],
            'excerpt' => ['required', 'string', 'max:512'],
            'content' => ['required', 'string'],
            'published' => ['boolean'],
            'is_new_thumbnail' => ['boolean'],
            'thumbnail' => $this->boolean('is_new_thumbnail')
                ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240']
                : ['nullable', 'string'],
            'tags' => ['nullable', 'array', 'max:10'],
            'tags.*' => ['string', 'max:255'],
        ];
    }
}
