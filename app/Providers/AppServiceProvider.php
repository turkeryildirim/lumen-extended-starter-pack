<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Illuminate\Contracts\Mail\Mailer', function ($app) {
            return $app['mailer'];
        });
    }
}
