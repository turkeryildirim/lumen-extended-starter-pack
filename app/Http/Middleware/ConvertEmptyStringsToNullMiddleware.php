<?php
namespace App\Http\Middleware;

/**
 * Class ConvertEmptyStringsToNullMiddleware
 *
 * @package App\Http\Middleware
 */
class ConvertEmptyStringsToNullMiddleware extends TransformsRequest
{
    /**
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        return is_string($value) && $value === '' ? null : $value;
    }
}
