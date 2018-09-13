<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

/**
 * Class PushChannel
 *
 * @package App\Notifications\Channels
 */
class PushChannel
{
    /**
     * @param                                        $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return boolean
     */
    public function send($notifiable, Notification $notification)
    {
        return $notification->toPush($notifiable);
    }
}
