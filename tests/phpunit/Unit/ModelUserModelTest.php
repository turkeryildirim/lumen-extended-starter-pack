<?php
namespace App\Tests\Unit;

use App\Models\UserMetaModel;
use App\Models\UserModel;
use App\Tests\TestCase;
use App\Transformers\UserModelTransformer;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class ModelUserModelTest
 *
 * @package App\Tests\Unit
 */
class ModelUserModelTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    public function testTransformer()
    {
        $user = factory(UserModel::class)->create();
        $this->assertEquals(UserModelTransformer::class, $user->transformer());
    }

    public function testCreateUser()
    {
        $user = factory(UserModel::class)->create();

        $this->assertInstanceOf(UserModel::class, $user);
        $this->seeInDatabase('users', ['id' => $user->id]);
    }

    public function testGetUser()
    {
        factory(UserModel::class)->create();
        $user = UserModel::find(1);

        $this->assertInstanceOf(UserModel::class, $user);
        $this->assertTrue($user->id == 1);
    }

    public function testUpdateUser()
    {
        factory(UserModel::class)->create();
        $user = UserModel::find(1);
        $user->email = 'update@test.com';
        $user->save();

        $this->seeInDatabase('users', ['email' => 'update@test.com', 'id' => $user->id]);
    }

    public function testDeleteUser()
    {
        factory(UserModel::class)->create();
        $user = UserModel::find(1);
        $user->delete();

        $this->seeInDatabase('users', ['id' => $user->id]);
        $this->assertTrue(!empty($user->deleted_at));
        $this->assertTrue(UserModel::all()->count() === 0);
    }

    public function testUserMetaMethod()
    {
        $users = factory(UserModel::class, 3)->create()
            ->each(function ($u) {
                $u->userMeta()->save(factory(UserMetaModel::class)->make(['user_id' => $u->id]));
            });
        $user = $users->random();
        $user_meta = UserMetaModel::findWhere(['user_id' => $user->id])->first();

        $this->assertTrue($user->userMeta->id === $user_meta->id);
    }

    public function testfindByAuthTokenMethod()
    {
        factory(UserModel::class, 3)->create();
        $user = UserModel::find(2);
        $user2 = UserModel::findByAuthToken($user->api_token);

        $this->assertTrue($user->id === $user2->id);
    }
}
