<?php
namespace App\Tests\Unit;

use App\Events\AuthenticationRegisterEvent;
use App\Models\UserModel;
use App\Tests\TestCase;

/**
 * Class EventAuthenticationRegisterEventTest
 *
 * @package App\Tests\Unit
 */
class EventAuthenticationRegisterEventTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testAuthenticationRegisterEvent()
    {
        $user = factory(UserModel::class)->create();
        $event = new AuthenticationRegisterEvent($user);

        $this->assertTrue($event->type == 'all');
        $this->assertNull($event->actionUser);
        $this->assertEquals($user->id, $event->actionModel->id);
    }
}
