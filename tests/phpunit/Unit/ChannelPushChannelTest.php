<?php
namespace App\Tests\Unit;

use App\Models\UserModel;
use App\Notifications\AuthenticationRegisterNotification;
use App\Notifications\Channels\PushChannel;
use App\Tests\TestCase;

/**
 * Class ChannelPushChannelTest
 *
 * @package App\Tests\Unit
 */
class ChannelPushChannelTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testSendMethod()
    {
        $user = factory(UserModel::class)->create();
        $notification = new AuthenticationRegisterNotification();
        $notification->setup($user, 'all');
        $push_channel = new PushChannel();
        $result = $push_channel->send($user, $notification);

        $this->assertTrue($result === true);
    }
}
