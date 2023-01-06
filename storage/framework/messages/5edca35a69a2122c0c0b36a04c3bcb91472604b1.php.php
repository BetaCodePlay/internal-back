<?php

namespace App\Users\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class Age implements Rule
{


    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $age = Carbon::createFromFormat('d-m-Y', $value)->age;
        return $age >= 18;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return _i('You do not meet the age to be registered, you must be 18+');
    }
}
