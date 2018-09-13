<?php
namespace App\Tests\Unit;

use App\Events\AuthenticationLoginEvent;
use App\Models\UserModel;
use App\Tests\TestCase;

/**
 * Class EventAuthenticationLoginEventTest
 *
 * @package App\Tests\Unit
 */
class EventAuthenticationLoginEventTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testAuthenticationLoginEvent()
    {
        $user = factory(UserModel::class)->create();
        $event = new AuthenticationLoginEvent($user);

        $this->assertTrue($event->type == 'email');
        $this->assertNull($event->actionUser);
        $this->assertEquals($user->id, $event->actionModel->id);
    }
}
