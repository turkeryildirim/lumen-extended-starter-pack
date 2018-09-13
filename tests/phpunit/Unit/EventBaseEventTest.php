<?php
namespace App\Tests\Unit;

use App\Events\BaseEvent;
use App\Models\UserModel;
use App\Tests\TestCase;

/**
 * Class EventBaseEventTest
 *
 * @package App\Tests\Unit
 */
class EventBaseEventTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testBaseEvent()
    {
        $user = factory(UserModel::class)->create();
        $mock = $this->getMockBuilder(BaseEvent::class)
            ->setConstructorArgs([$user, 'all'])
            ->getMockForAbstractClass();

        $this->assertTrue($mock->type == 'all');
        $this->assertNull($mock->actionUser);
        $this->assertEquals($user->id, $mock->actionModel->id);
    }
}
