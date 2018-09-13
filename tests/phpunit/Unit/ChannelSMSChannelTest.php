<?php
namespace App\Tests\Unit;

use App\Models\UserModel;
use App\Notifications\AuthenticationRegisterNotification;
use App\Notifications\Channels\SMSChannel;
use App\Tests\TestCase;

/**
 * Class ChannelSMSChannelTest
 *
 * @package App\Tests\Unit
 */
class ChannelSMSChannelTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testSendMethod()
    {
        $user = factory(UserModel::class)->create();
        $user->userMeta->phone = '1234567980';
        $user->userMeta->save();
        $notification = new AuthenticationRegisterNotification();
        $notification->setup($user, 'all');
        $sms_channel = new SMSChannel();
        $result = $sms_channel->send($user, $notification);

        $this->assertTrue($result === true);
    }
}
