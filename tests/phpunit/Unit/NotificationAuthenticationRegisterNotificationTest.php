<?php
namespace App\Tests\Unit;

use App\Mail\AuthenticationRegisterMail;
use App\Models\UserModel;
use App\Notifications\AuthenticationRegisterNotification;
use App\Tests\TestCase;
use Illuminate\Support\Facades\Mail;

/**
 * Class NotificationAuthenticationRegisterNotificationTest
 *
 * @package App\Tests\Unit
 */
class NotificationAuthenticationRegisterNotificationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testToEmailMethod()
    {
        $user = factory(UserModel::class)->create();
        $notification = new AuthenticationRegisterNotification();
        $notification->setup($user, 'all');
        $notification->toEmail('');

        Mail::assertQueued(AuthenticationRegisterMail::class);
    }

    public function testToPushMethod()
    {
        $user = factory(UserModel::class)->create();
        $notification = new AuthenticationRegisterNotification();
        $notification->setup($user, 'all');
        $result = $notification->toPush('');

        $this->assertTrue($result === true);
    }

    public function testToSmsMethod()
    {
        $user = factory(UserModel::class)->create();
        $user->userMeta->phone = '1234567980';
        $user->userMeta->save();
        $notification = new AuthenticationRegisterNotification();
        $notification->setup($user, 'all');
        $result = $notification->toSMS('');

        $this->assertTrue($result === true);

        $user->userMeta->phone = null;
        $user->userMeta->save();
        $notification->setup($user, 'all');
        $result = $notification->toSMS('');

        $this->assertNull($result);
    }

    public function testToDatabaseMethod()
    {
        $user = factory(UserModel::class)->create();
        $notification = new AuthenticationRegisterNotification();
        $notification->setup($user, 'all');
        $result = $notification->toDatabase('');

        $this->assertContains($user->id, $result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('message', $result);
    }
}
