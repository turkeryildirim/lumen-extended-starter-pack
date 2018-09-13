<?php

namespace App\Providers;

use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuthServiceProvider
 *
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        // !important use fully qualified class name
        Gate::policy(\App\Models\UserModel::class, \App\Policies\UserPolicy::class);

        Auth::viaRequest('api', function ($request) {
            if (empty($request) || empty($request->header('Authorization'))) {
                return null;
            }
            if (!$user = UserModel::findByAuthToken($request->header('Authorization'))) {
                return null;
            }

            return $user;
        });
    }
}
