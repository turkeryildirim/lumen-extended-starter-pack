<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

/**
 * Class ValidationMiddleware
 *
 * @package App\Http\Middleware
 */
class ValidationMiddleware
{
    use ProvidesConvenienceMethods;

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(Request $request, Closure $next)
    {
        $file_name = str_replace(
            'App\\Http\\Controllers\\',
            '',
            str_replace(
                '@',
                '/',
                $request->route()[1]['uses']
            )
        );
        $current_path = dirname(__FILE__);
        $file_path = str_replace('Middleware', 'Validations', $current_path).'/'.$file_name.'.php';

        if (file_exists($file_path) && is_file($file_path)) {
            $rules = require($file_path);

            if (!empty($rules) && is_array($rules)) {
                $this->validate($request, $rules);
            }
        }

        return $next($request);
    }
}
