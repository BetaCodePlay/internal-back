<?php

namespace App\Users\Rules;

use Illuminate\Contracts\Validation\Rule;

class DNI implements Rule
{
    private $regex;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($regex)
    {
        $this->regex = $regex;
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
        return preg_match($this->regex, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return _i('The DNI entered does not match the format used in your country');
    }
}
