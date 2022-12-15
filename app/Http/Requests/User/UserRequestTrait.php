<?php
namespace App\Http\Requests\User;
use Illuminate\Validation\Rule;

trait UserRequestTrait {
    protected function userRules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'roles' => ['required', 'string', Rule::in('admin','teacher','student')],
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'address' => 'required|string',
            'postcode' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'birthday' =>  ['required','date'],
        ];
    }
}
