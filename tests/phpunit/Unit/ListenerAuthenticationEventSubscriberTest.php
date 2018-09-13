<?php
namespace App\Tests\Unit;

use App\Events\AuthenticationLoginEvent;
use App\Events\AuthenticationRegisterEvent;
use App\Listeners\AuthenticationEventSubscriber;
use App\Models\UserModel;
use App\Notifications\AuthenticationLoginNotification;
use App\Notifications\AuthenticationRegisterNotification;
use App\Tests\TestCase;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Notification;

/**
 * Class ListenerAuthenticationEventSubscriberTest
 *
 * @package App\Tests\Unit
 */
class ListenerAuthenticationEventSubscriberTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testSubscribeMethod()
    {
        $dispacher = new Dispatcher();
        $subscriber = new AuthenticationEventSubscriber();
        $subscriber->subscribe($dispacher);

        $this->assertCount(1, $dispacher->getListeners(AuthenticationLoginEvent::class));
        $this->assertCount(1, $dispacher->getListeners(AuthenticationRegisterEvent::class));
    }

    public function testOnLoginMethod()
    {
        $user = factory(UserModel::class)->create();
        $event = new AuthenticationLoginEvent($user);
        $subscriber = new AuthenticationEventSubscriber();
        $subscriber->onLogin($event);

        Notification::assertSentTo($user, AuthenticationLoginNotification::class);
    }

    public function testOnRegisterMethod()
    {
        $user = factory(UserModel::class)->create();
        $event = new AuthenticationRegisterEvent($user);
        $subscriber = new AuthenticationEventSubscriber();
        $subscriber->onRegister($event);

        Notification::assertSentTo($user, AuthenticationRegisterNotification::class);
    }
}
