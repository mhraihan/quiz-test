<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Trimmed implements Rule
{
    public function passes($attribute, $value)
    {
        return !empty(trim($value));
    }

    public function message()
    {
        return 'The :attribute must not be empty after trimming.';
    }
}
