<?php

namespace App\Http\Middleware;

/**
 * Class TrimStringsMiddleware
 *
 * @package App\Http\Middleware
 */
class TrimStringsMiddleware extends TransformsRequest
{
    /**
     * @var array
     */
    protected $except = [
        'password',
        'password_confirmation',
    ];

    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        if (in_array($key, $this->except, true)) {
            return $value;
        }

        return is_string($value) ? trim($value) : $value;
    }
}
