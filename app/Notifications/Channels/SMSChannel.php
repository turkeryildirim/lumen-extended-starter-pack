<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

/**
 * Class SMSChannel
 *
 * @package App\Notifications\Channels
 */
class SMSChannel
{
    /**
     * @param                                        $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return boolean
     */
    public function send($notifiable, Notification $notification)
    {
        return $notification->toSMS($notifiable);
    }
}
