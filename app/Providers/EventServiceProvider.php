<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 *
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $listen = [
        //
    ];

    /**
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\AuthenticationEventSubscriber',
    ];

    public function boot()
    {
        parent::boot();
    }
}
