<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordRegex implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/', $value);
    }

    public function message()
    {
        return 'The :attribute must contain at least one capital letter, one small letter, one number, and one special character.';
    }
}
