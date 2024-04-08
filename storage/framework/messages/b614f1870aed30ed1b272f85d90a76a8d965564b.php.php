<?php

namespace Dotworkers\Configurations\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;
use Dotworkers\Configurations\Configurations;

class CleanGmailAddress extends TransformsRequest
{
    /**
     * The attributes that should not be trimmed.
     *
     * @var array
     */
    protected $only = [
        'email'
    ];

    /**
     * Transform the given value.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        $registrationSettings = Configurations::getRegistration();
        if ($registrationSettings->email->clean_gmail) {
            if (in_array($key, $this->only, true)) {
                if (!is_null($value)) {
                    $terms = explode('@', $value);
                    if (isset($terms[1])) {
                        if ($terms[1] == 'gmail.com') {
                            $terms[0] = str_replace('.', '', $terms[0]);
                            $value = $terms[0] . '@' . $terms[1];
                        }
                    }
                }
                return $value;
            }
            return $value;
        }
        return $value;   
    }
}