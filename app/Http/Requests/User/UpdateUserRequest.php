<?php

namespace App\Http\Requests\User;

use App\Enums\UserEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    use UserRequestTrait;

    public function authorize(): bool
    {
        if ($this->route()->getName() === 'user.profile.update') {
            return true;
        }
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $updateRules = [...$this->userRules(),
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore(request()->id)],
            'password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
        ];

        if ($this->route()->getName() === 'user.profile.update') {
            unset($updateRules['roles']);
        }

        return $updateRules;
    }

}
