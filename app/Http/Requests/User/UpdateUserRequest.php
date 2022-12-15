<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
class UpdateUserRequest extends FormRequest
{
    use UserRequestTrait;

    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return array_merge($this->userRules(), [
            'email' => ['email', 'max:255', Rule::unique(User::class)->ignore(request()->id)],
            'password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
        ]);
    }
}
