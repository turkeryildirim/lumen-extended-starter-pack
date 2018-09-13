<?php

namespace App\Listeners;

use App\Notifications\AuthenticationLoginNotification;
use App\Notifications\AuthenticationRegisterNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

/**
 * Class AuthenticationEventSubscriber
 *
 * @package App\Listeners
 */
class AuthenticationEventSubscriber
{
    use InteractsWithQueue;
    /**
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\AuthenticationLoginEvent',
            'App\Listeners\AuthenticationEventSubscriber@onLogin'
        );

        $events->listen(
            'App\Events\AuthenticationRegisterEvent',
            'App\Listeners\AuthenticationEventSubscriber@onRegister'
        );
    }

    /**
     * @param $event
     */
    public function onLogin($event)
    {
        $notification = new AuthenticationLoginNotification();
        $notification->setup($event->actionModel, $event->type, $event->actionUser);
        Notification::send($event->actionModel, $notification);
    }

    /**
     * @param $event
     */
    public function onRegister($event)
    {
        $notification = new AuthenticationRegisterNotification();
        $notification->setup($event->actionModel, $event->type, $event->actionUser);
        Notification::send($event->actionModel, $notification);
    }
}
