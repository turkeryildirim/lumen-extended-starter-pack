<?php

namespace App\Http\Middleware;

use App\Models\UserModel;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;

/**
 * Class AuthenticateMiddleware
 *
 * @package App\Http\Middleware
 */
class AuthenticateMiddleware
{
    protected $auth;

    /**
     * @param \Illuminate\Contracts\Auth\Factory $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory|mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (empty($request->header('Authorization'))) {
            return response()
                ->json([
                    'success' => false,
                    'status' => 401,
                    'error' => [
                        'code' => 401,
                        'message' => 'HTTP_UNAUTHORIZED'
                    ]
                ], 401);
        }

        if ($this->auth->guard($guard)->guest()) {
            return response()
                ->json([
                    'success' => false,
                    'status' => 403,
                    'error' => [
                        'code' => 401,
                        'message' => 'HTTP_FORBIDDEN'
                    ]
                ], 403);
        }

        return $next($request);
    }
}
