<?php

namespace App\Http\Requests\Admin;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserRequest extends FormRequest
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Authorize here (not only in the controller) so precognitive
     * validation requests are also restricted to allowed users.
     */
    public function authorize(): bool
    {
        $user = $this->route('user');

        return $user instanceof User
            ? $this->user()->can('update', $user)
            : $this->user()->can('create', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        $isUpdating = $user instanceof User;

        return [
            ...$this->profileRules($isUpdating ? $user->id : null),
            'role' => ['nullable', 'string', Rule::exists(Role::class, 'name')],
            'password' => $isUpdating
                ? ['nullable', 'string', Password::default(), 'confirmed']
                : $this->passwordRules(),
        ];
    }
}
