<?php

namespace App\Http\Requests\Admin;

use App\Models\Permission;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
    /**
     * Authorize here (not only in the controller) so precognitive
     * validation requests are also restricted to allowed users.
     */
    public function authorize(): bool
    {
        $permission = $this->route('permission');

        return $permission instanceof Permission
            ? $this->user()->can('update', $permission)
            : $this->user()->can('create', Permission::class);
    }

    /**
     * Names follow the `resource.verb.attribute` format (e.g. `posts.view.unpublished`).
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $permission = $this->route('permission');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z][a-zA-Z]*(\.[a-z][a-zA-Z]*){1,2}$/',
                Rule::notIn(Permission::systemPermissions()),
                Rule::unique(Permission::class, 'name')->ignore($permission instanceof Permission ? $permission->id : null),
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.regex' => 'El nombre debe tener el formato recurso.acción o recurso.acción.atributo (p. ej. posts.publish).',
            'name.not_in' => 'Este nombre está reservado para un permiso del sistema.',
        ];
    }
}
