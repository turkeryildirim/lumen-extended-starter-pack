<?php

namespace App\Notifications;

use App\Mail\AuthenticationLoginMail;
use App\Models\UserModel;
use App\Services\EmailService;

/**
 * Class AuthenticationLoginNotification
 *
 * @package App\Notifications\User
 */
class AuthenticationLoginNotification extends BaseNotification
{
    public function setup($actionModel, $type = 'all', UserModel $actionUser = null)
    {
        parent::setup($actionModel, $type, $actionUser);
        $this->setEmailService(new EmailService());
    }

    /**
     * @param $notifiable
     * @return mixed
     * @throws \Exception
     */
    public function toEmail($notifiable)
    {
        $mailable = new AuthenticationLoginMail($this->actionModel, $this->actionUser);
        return  $this->emailService->setTo($this->actionModel->email)
            ->send($mailable, true);
    }
}
