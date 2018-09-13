<?php
namespace App\Tests\Unit;

use App\Models\UserMetaModel;
use App\Models\UserModel;
use App\Tests\TestCase;
use App\Transformers\UserMetaModelTransformer;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class ModelUserMetaModelTest
 *
 * @package App\Tests\Unit
 */
class ModelUserMetaModelTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }
    public function testTransformer()
    {
        $user_meta = factory(UserMetaModel::class)->create();
        $this->assertEquals(UserMetaModelTransformer::class, $user_meta->transformer());
    }

    public function testCreateUserMeta()
    {
        $user_meta = factory(UserMetaModel::class)->create();

        $this->assertInstanceOf(UserMetaModel::class, $user_meta);
        $this->seeInDatabase('user_meta', ['id' => $user_meta->id]);
    }

    public function testGetUserMeta()
    {
        factory(UserMetaModel::class)->create();
        $user_meta = UserMetaModel::find(1);

        $this->assertInstanceOf(UserMetaModel::class, $user_meta);
        $this->assertTrue($user_meta->id == 1);
    }

    public function testUpdateUserMeta()
    {
        factory(UserMetaModel::class)->create();
        $user_meta = UserMetaModel::find(1);
        $user_meta->city = 'testing';
        $user_meta->save();

        $this->seeInDatabase('user_meta', ['city' => 'testing', 'id' => $user_meta->id]);
    }

    public function testDeleteUserMeta()
    {
        factory(UserModel::class)->create();
        $user_meta = UserMetaModel::find(1);
        $user_meta->delete();

        $this->seeInDatabase('user_meta', ['id' => $user_meta->id]);
        $this->assertTrue(!empty($user_meta->deleted_at));
        $this->assertTrue(UserMetaModel::all()->count() === 0);
    }

    public function testUserMethod()
    {
        $user_metas = factory(UserMetaModel::class, 3)->create();
        $user_meta = $user_metas->random();
        $user = $user_meta->user()->first();

        $this->assertInstanceOf(UserModel::class, $user);
        $this->assertTrue($user->id == $user_meta->user_id);
    }
}
