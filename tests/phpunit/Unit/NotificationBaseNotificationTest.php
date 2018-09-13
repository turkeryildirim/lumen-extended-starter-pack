<?php
namespace App\Tests\Unit;

use App\Models\UserModel;
use App\Notifications\BaseNotification;
use App\Services\EmailService;
use App\Services\PushNotificationService;
use App\Services\SmsService;
use App\Tests\TestCase;

/**
 * Class NotificationBaseNotificationTest
 *
 * @package App\Tests\Unit
 */
class NotificationBaseNotificationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testViaMethod()
    {
        $user = factory(UserModel::class)->create();
        $class = new BaseNotification();
        $class->setup($user, 'all');
        $result = $class->via('');

        $this->assertCount(4, $result);
        $this->assertContains('database', $result);
        $this->assertContains(\App\Notifications\Channels\EmailChannel::class, $result);
        $this->assertContains(\App\Notifications\Channels\SMSChannel::class, $result);
        $this->assertContains(\App\Notifications\Channels\PushChannel::class, $result);

        $class->setup($user, 'email');
        $result = $class->via('');

        $this->assertCount(1, $result);
        $this->assertContains(\App\Notifications\Channels\EmailChannel::class, $result);

        $class->setup($user, 'database');
        $result = $class->via('');

        $this->assertCount(1, $result);
        $this->assertContains('database', $result);

        $class->setup($user, 'sms');
        $result = $class->via('');

        $this->assertCount(1, $result);
        $this->assertContains(\App\Notifications\Channels\SMSChannel::class, $result);

        $class->setup($user, 'push');
        $result = $class->via('');

        $this->assertCount(1, $result);
        $this->assertContains(\App\Notifications\Channels\PushChannel::class, $result);

        $class->setup($user, ['database','push']);
        $result = $class->via('');

        $this->assertCount(2, $result);
        $this->assertContains('database', $result);
        $this->assertContains(\App\Notifications\Channels\PushChannel::class, $result);
    }

    public function testSetSmsServiceMethod()
    {
        $user = factory(UserModel::class)->create();
        $class = new BaseNotification();
        $class->setSmsService(new SmsService())
            ->setup($user, 'all');

        $this->assertNotNull($class->smsService);
    }

    public function testSetPushServiceMethod()
    {
        $user = factory(UserModel::class)->create();
        $class = new BaseNotification();
        $class->setPushService(new PushNotificationService())
            ->setup($user, 'all');

        $this->assertNotNull($class->pushService);
    }

    public function testSetEmailServiceMethod()
    {
        $user = factory(UserModel::class)->create();
        $class = new BaseNotification();
        $class->setEmailService(new EmailService())
            ->setup($user, 'all');

        $this->assertNotNull($class->emailService);
    }
}
