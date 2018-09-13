<?php
namespace App\Tests\Unit;

use App\Models\UserModel;
use App\Tests\TestCase;
use App\Transformers\UserModelTransformer;

/**
 * Class TransformerUserModelTransformerTest
 *
 * @package App\Tests\Unit
 */
class TransformerUserModelTransformerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testTransformation()
    {
        $user = factory(UserModel::class)->create();
        $result = (new UserModelTransformer())->transform(UserModel::getFirst());

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('first_name', $result);
        $this->assertArrayHasKey('last_name', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('role', $result);
        $this->assertArrayHasKey('authorization', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('created_at', $result);
        $this->assertArrayHasKey('updated_at', $result);

        $this->assertArrayNotHasKey('deleted_at', $result);
        $this->assertArrayNotHasKey('activation_date', $result);
        $this->assertArrayNotHasKey('last_login_date', $result);
        $this->assertArrayNotHasKey('password', $result);
        $this->assertArrayNotHasKey('activation_code', $result);

        $this->assertTrue($user->id === $result['id']);
    }
}
