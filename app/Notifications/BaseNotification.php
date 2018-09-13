<?php

namespace App\Notifications;

use App\Models\UserModel;
use App\Notifications\Channels\EmailChannel;
use App\Notifications\Channels\PushChannel;
use App\Notifications\Channels\SMSChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

/**
 * Class BaseNotification
 *
 * @package App\Notifications\User
 */
class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var
     */
    public $actionModel;

    /**
     * @var \App\Models\UserModel|null
     */
    public $actionUser;

    /**
     * @var
     */
    private $type;

    /**
     * @var \App\Services\EmailService;
     */
    public $emailService;

    /**
     * @var \App\Services\SmsService;
     */
    public $smsService;

    /**
     * @var \App\Services\PushNotificationService
     */
    public $pushService;

    /**
     * BaseNotification constructor.
     *
     * @param $actionModel
     * @param array|string $type
     * @param \App\Models\UserModel|null $actionUser
     */
    public function setup($actionModel, $type = 'all', UserModel $actionUser = null)
    {
        $this->actionModel = $actionModel;
        $this->actionUser = (empty($actionUser)) ? Auth::user() : $actionUser;
        $this->type = $type;
    }

    /**
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if ($this->type == 'all') {
            return [EmailChannel::class, 'database', SMSChannel::class, PushChannel::class];
        }

        if (!is_array($this->type)) {
            $this->type = [$this->type];
        }

        foreach ($this->type as $key => $type) {
            if ($type === 'sms') {
                $this->type[$key] = SMSChannel::class;
            }

            if ($type === 'push') {
                $this->type[$key] = PushChannel::class;
            }

            if ($type === 'email') {
                $this->type[$key] = EmailChannel::class;
            }
        }

        return $this->type;
    }

    /**
     * @param \App\Services\EmailService $emailService
     * @return $this
     */
    public function setEmailService(\App\Services\EmailService $emailService)
    {
        $this->emailService = $emailService;
        return $this;
    }

    /**
     * @param \App\Services\SmsService $smsService
     * @return $this
     */
    public function setSmsService(\App\Services\SmsService $smsService)
    {
        $this->smsService = $smsService;
        return $this;
    }

    /**
     * @param \App\Services\PushNotificationService $pushService
     * @return $this
     */
    public function setPushService(\App\Services\PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
        return $this;
    }
}
