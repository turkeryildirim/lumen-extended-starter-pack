<?php
namespace App\Tests\Unit;

use App\Mail\AuthenticationLoginMail;
use App\Models\UserModel;
use App\Notifications\AuthenticationLoginNotification;
use App\Services\EmailService;
use App\Tests\TestCase;
use Illuminate\Support\Facades\Mail;

/**
 * Class NotificationAuthenticationLoginNotificationTest
 *
 * @package App\Tests\Unit
 */
class NotificationAuthenticationLoginNotificationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testToEmailMethod()
    {
        $user = factory(UserModel::class)->create();
        $notification = new AuthenticationLoginNotification();
        $notification->setup($user, 'all');
        $notification->setEmailService(new EmailService());
        $result = $notification->toEmail('');

        Mail::assertQueued(AuthenticationLoginMail::class);
    }
}
