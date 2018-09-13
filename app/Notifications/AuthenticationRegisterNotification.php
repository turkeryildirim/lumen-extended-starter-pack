<?php

namespace App\Notifications;

use App\Mail\AuthenticationRegisterMail;
use App\Models\UserModel;
use App\Services\EmailService;
use App\Services\PushNotificationService;
use App\Services\SmsService;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class AuthenticationRegisterNotification
 *
 * @package App\Notifications
 */
class AuthenticationRegisterNotification extends BaseNotification
{
    public function setup($actionModel, $type = 'all', UserModel $actionUser = null)
    {
        parent::setup($actionModel, $type, $actionUser);
        $this->setEmailService(new EmailService())
            ->setPushService(new PushNotificationService())
            ->setSmsService(new SmsService());
    }

    /**
     * @param $notifiable
     * @return mixed
     * @throws \Exception
     */
    public function toEmail($notifiable)
    {
        $mailable = new AuthenticationRegisterMail($this->actionModel, $this->actionUser);
        return  $this->emailService->setTo($this->actionModel->email)
            ->send($mailable);
    }

    /**
     * @param $notifiable
     * @return bool|mixed
     * @throws \Exception
     */
    public function toSMS($notifiable)
    {
        if (!empty($this->actionModel->userMeta->phone)) {
            return $this->smsService->setTo($this->actionModel->userMeta->phone)
                ->setMessage("You have registered")
                ->send();
        }
    }

    /**
     * @param $notifiable
     * @return bool|mixed
     * @throws \Exception
     */
    public function toPush($notifiable)
    {
        return $this->pushService->setTitle("Your registration")
            ->setMessage("Thanks for your registration")
            ->setTo($this->actionModel->id)
            ->send();
    }

    /**
     * @param $notifiable
     * @return array|mixed
     */
    public function toDatabase($notifiable)
    {
        return [
            'id' => $this->actionModel->id,
            'message' => "Thanks for your registration",
        ];
    }
}
