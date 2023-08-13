<?php

namespace App\Http\Requests\User;

use App\Enums\UserEnum;
use Illuminate\Validation\Rule;

trait UserRequestTrait
{
    protected function baseUserRules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'roles' => ['required', 'string', Rule::in('admin', 'teacher', 'student')],
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'address' => 'required|string',
            'postcode' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'birthday' => ['required', 'date'],
        ];
    }

    protected function studentSpecificRules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'required|integer|exists:users,id',
        ];
    }

    protected function teacherSpecificRules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
        ];
    }

    /**
     * Returns the rules for creating or updating a user.
     *
     * The rules will vary depending on the role of the user being created or updated.
     */
    protected function userRules(): array
    {
        $rules = $this->baseUserRules();
        if (($this->route()->getName() === 'admin.users.update') || ($this->route()->getName() === 'admin.users.store')) {
            if (request()->input('roles') === UserEnum::STUDENT->value) {
                $rules = [...$rules, ...$this->studentSpecificRules()];
            }

            if (request()->input('roles') === UserEnum::TEACHER->value) {
                $rules = [...$rules, ...$this->teacherSpecificRules()];
            }
        }
        return $rules;
    }
}
