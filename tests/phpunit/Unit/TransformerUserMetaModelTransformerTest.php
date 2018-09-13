<?php
namespace App\Tests\Unit;

use App\Models\UserMetaModel;
use App\Models\UserModel;
use App\Tests\TestCase;
use App\Transformers\UserMetaModelTransformer;

/**
 * Class TransformerUserMetaModelTransformerTest
 *
 * @package App\Tests\Unit
 */
class TransformerUserMetaModelTransformerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testTransformation()
    {
        $user = factory(UserModel::class)->create();
        $result = (new UserMetaModelTransformer())->transform(UserMetaModel::getFirst());

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('user_id', $result);
        $this->assertArrayHasKey('gender', $result);
        $this->assertArrayHasKey('phone', $result);
        $this->assertArrayHasKey('city', $result);
        $this->assertArrayHasKey('address', $result);
        $this->assertArrayHasKey('birth_date', $result);
        $this->assertArrayHasKey('created_at', $result);
        $this->assertArrayHasKey('updated_at', $result);
        $this->assertTrue($user->userMeta->id === $result['id']);
        $this->assertTrue($user->id === $result['user_id']);
    }
}
