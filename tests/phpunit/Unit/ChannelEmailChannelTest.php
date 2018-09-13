<?php
namespace App\Tests\Unit;

use App\Models\UserModel;
use App\Notifications\AuthenticationRegisterNotification;
use App\Notifications\Channels\EmailChannel;
use App\Tests\TestCase;

/**
 * Class ChannelEmailChannelTest
 *
 * @package App\Tests\Unit
 */
class ChannelEmailChannelTest extends TestCase
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
        $email_channel = new EmailChannel();
        $result = $email_channel->send($user, $notification);

        $this->assertNull($result);
    }
}
