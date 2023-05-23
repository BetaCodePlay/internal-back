<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\TransformsRequest;
use Illuminate\Http\Request;

class CleanGmailAddress extends TransformsRequest
{
    /**
     * The attributes that should transformed
     *
     * @var array
     */
    protected $only = [
        'email'
    ];

    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        $request = new Request();
        if (in_array($key, $this->only, true)) {
            if (!is_null($value)) {
                $terms = explode('@', $value);

                if ($terms[1] == 'gmail.com') {
                    $value = str_replace('.', '', $value);
                }
            }
            return $value;
        }

        return $value;
    }
}