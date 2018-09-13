<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

/**
 * Class EmailChannel
 *
 * @package App\Notifications\Channels
 */
class EmailChannel
{
    /**
     * @param                                        $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return boolean
     */
    public function send($notifiable, Notification $notification)
    {
        return $notification->toEmail($notifiable);
    }
}
