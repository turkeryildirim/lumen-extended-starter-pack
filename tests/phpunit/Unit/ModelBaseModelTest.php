<?php
namespace App\Tests\Unit;

use App\Models\UserModel;
use App\Tests\TestCase;
use BadMethodCallException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class ModelBaseModelTest
 *
 * @package App\Tests\Unit
 */
class ModelBaseModelTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
    }

    public function testGetAllMethod()
    {
        factory(UserModel::class, 5)->create();
        $users = UserModel::getAll(['id', 'email'], ['userMeta']);
        $user = $users->random()
            ->toArray();

        $this->assertCount(5, $users);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('user_meta', $user);
        $this->assertArrayNotHasKey('password', $user);
    }

    public function testGetAllError()
    {
        factory(UserModel::class)->create();
        $test = UserModel::getAll(['id', 'email'], ['userMetas']);

        $this->assertInstanceOf(RelationNotFoundException::class, $test);
    }

    public function testGetFirstMethod()
    {
        factory(UserModel::class, 3)->create();

        $users = UserModel::getAll();
        $user = UserModel::getFirst(['id', 'email'], ['userMeta'])
            ->toArray();

        $this->assertCount(3, $users);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('user_meta', $user);
        $this->assertArrayNotHasKey('password', $user);
        $this->assertTrue($user['id'] === $users->first()->id);
    }

    public function testGetFirstMethodError()
    {
        factory(UserModel::class)->create();
        $test = UserModel::getFirst(['id', 'email'], ['userMetas']);

        $this->assertInstanceOf(RelationNotFoundException::class, $test);
    }

    public function testGetLastMethod()
    {
        factory(UserModel::class, 3)->create();

        $users = UserModel::getAll();
        $user = UserModel::getLast(['id', 'email'], ['userMeta'])
            ->toArray();

        $this->assertCount(3, $users);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('user_meta', $user);
        $this->assertArrayNotHasKey('password', $user);
        $this->assertTrue($user['id'] === $users->last()->id);
    }

    public function testGetLastMethodError()
    {
        factory(UserModel::class)->create();
        $test = UserModel::getLast(['id', 'email'], ['userMetas']);

        $this->assertInstanceOf(RelationNotFoundException::class, $test);
    }

    public function testFindByMethod()
    {
        factory(UserModel::class, 3)->create();

        $users = UserModel::getAll();
        $user = UserModel::findBy('id', 2, ['id', 'email'], ['userMeta'])
            ->first()
            ->toArray();

        $this->assertCount(3, $users);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('user_meta', $user);
        $this->assertArrayNotHasKey('password', $user);
        $this->assertTrue($user['id'] === $users->get(1)->id);
    }

    public function testFindByMethodError()
    {
        factory(UserModel::class)->create();
        $test = UserModel::findBy('id', 1, ['id', 'email'], ['userMetas']);

        $this->assertInstanceOf(RelationNotFoundException::class, $test);
    }

    public function testFindWhereMethod()
    {
        factory(UserModel::class, 3)->create();

        $users = UserModel::getAll();
        $user = UserModel::findWhere([['email', '=', $users->first()->email]], ['id', 'email'], ['userMeta'])
            ->first()
            ->toArray();

        $this->assertCount(3, $users);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('user_meta', $user);
        $this->assertArrayNotHasKey('password', $user);
        $this->assertTrue($user['id'] === $users->first()->id);
    }

    public function testFindWhereMethodError()
    {
        $user = factory(UserModel::class)->create();
        $test = UserModel::findWhere(['email' => $user->email], ['id', 'email'], ['userMetas']);

        $this->assertInstanceOf(RelationNotFoundException::class, $test);
    }

    public function testFindWhereInMethod()
    {
        factory(UserModel::class, 3)->create();

        $users = UserModel::getAll();
        $users2 = UserModel::findWhereIn('email', $users->pluck('email')->toArray(), ['id', 'email'], ['userMeta']);
        $user = $users2->first()
            ->toArray();

        $this->assertCount(3, $users);
        $this->assertCount(3, $users2);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('user_meta', $user);
        $this->assertArrayNotHasKey('password', $user);
        $this->assertTrue($user['id'] === $users2->first()->id);
    }

    public function testFindWhereInMethodError()
    {
        $user = factory(UserModel::class)->create();
        $test = UserModel::findWhereIn('email', $user->pluck('email')->toArray(), ['id', 'email'], ['userMetas']);

        $this->assertInstanceOf(RelationNotFoundException::class, $test);
    }

    public function testFindWhereNotInMethod()
    {
        factory(UserModel::class, 3)->create();

        $users = UserModel::getAll();
        $users2 = UserModel::findWhereNotIn('id', [$users->first()->id], ['id', 'email'], ['userMeta']);
        $user = $users2->first()
            ->toArray();

        $this->assertCount(3, $users);
        $this->assertCount(2, $users2);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('user_meta', $user);
        $this->assertArrayNotHasKey('password', $user);
        $this->assertTrue($user['id'] === $users2->first()->id);
        $this->assertTrue($user['id'] !== $users->first()->id);
    }

    public function testFindWhereNotInMethodError()
    {
        factory(UserModel::class)->create();
        $test = UserModel::findWhereNotIn('id', [2], ['id', 'email'], ['userMetas']);

        $this->assertInstanceOf(RelationNotFoundException::class, $test);
    }

    public function testFindWhereBetweenMethod()
    {
        factory(UserModel::class, 5)->create();

        $users = UserModel::getAll();
        $users2 = UserModel::findWhereBetween('id', [3, 5], ['id', 'email'], ['userMeta']);
        $user = $users2->first()
            ->toArray();

        $this->assertCount(5, $users);
        $this->assertCount(3, $users2);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('user_meta', $user);
        $this->assertArrayNotHasKey('password', $user);
        $this->assertTrue($user['id'] === $users2->first()->id);
        $this->assertTrue($user['id'] !== $users->first()->id);
    }

    public function testFindWhereBetweenMethodError()
    {
        factory(UserModel::class, 5)->create();
        $test = UserModel::findWhereBetween('id', [2, 4], ['id', 'email'], ['userMetas']);

        $this->assertInstanceOf(RelationNotFoundException::class, $test);
    }

    public function testFindWhereNotBetweenMethod()
    {
        factory(UserModel::class, 5)->create();

        $users = UserModel::getAll();
        $users2 = UserModel::findWhereNotBetween('id', [1, 3], ['id', 'email'], ['userMeta']);
        $user = $users2->first()
            ->toArray();

        $this->assertCount(5, $users);
        $this->assertCount(2, $users2);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('user_meta', $user);
        $this->assertArrayNotHasKey('password', $user);
        $this->assertTrue($user['id'] === $users2->first()->id);
        $this->assertTrue($user['id'] !== $users->first()->id);
    }

    public function testFindWhereNotBetweenMethodError()
    {
        factory(UserModel::class, 5)->create();
        $test = UserModel::findWhereNotBetween('id', [2, 4], ['id', 'email'], ['userMetas']);

        $this->assertInstanceOf(RelationNotFoundException::class, $test);
    }

    public function testFindWhereNullMethod()
    {
        factory(UserModel::class)->create();
        factory(UserModel::class)->create([
            'first_name' => null
        ]);

        $user = UserModel::findWhereNull('first_name', ['id', 'first_name'], ['userMeta'])
            ->first()
            ->toArray();

        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('first_name', $user);
        $this->assertArrayHasKey('user_meta', $user);
        $this->assertArrayNotHasKey('password', $user);
        $this->assertTrue($user['first_name'] === null);
    }

    public function testFindWhereNullMethodError()
    {
        factory(UserModel::class)->create();
        factory(UserModel::class)->create([
            'first_name' => null
        ]);
        $test = UserModel::findWhereNull('first_name', ['id', 'first_name'], ['userMetas']);

        $this->assertInstanceOf(RelationNotFoundException::class, $test);
    }

    public function testFindWhereNotNullMethod()
    {
        factory(UserModel::class)->create([
            'first_name' => null
        ]);
        factory(UserModel::class)->create();

        $user = UserModel::findWhereNotNull('first_name', ['id', 'first_name'], ['userMeta'])
            ->first()
            ->toArray();

        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('first_name', $user);
        $this->assertArrayHasKey('user_meta', $user);
        $this->assertArrayNotHasKey('password', $user);
        $this->assertTrue($user['first_name'] !== null);
    }

    public function testFindWhereNotNullMethodError()
    {
        factory(UserModel::class)->create();
        factory(UserModel::class)->create([
            'first_name' => null
        ]);
        $test = UserModel::findWhereNotNull('first_name', ['id', 'first_name'], ['userMetas']);

        $this->assertInstanceOf(RelationNotFoundException::class, $test);
    }

    public function testFindWhereHasMethod()
    {
        $users = factory(UserModel::class, 2)->create();
        $user = UserModel::findWhereHas('userMeta', function ($q) use ($users) {
            return $q->where('phone', $users->first()->userMeta->phone);
        }, ['id', 'first_name'], ['userMeta'])
            ->first()
            ->toArray();

        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('first_name', $user);
        $this->assertArrayHasKey('user_meta', $user);
        $this->assertArrayNotHasKey('password', $user);
        $this->assertTrue($user['user_meta']['phone'] === $users->first()->userMeta->phone);
    }

    public function testFindWhereHasNotNullMethodError()
    {
        factory(UserModel::class, 2)->create();
        $test = UserModel::findWhereHas('userMetas');

        $this->assertInstanceOf(BadMethodCallException::class, $test);
    }

    public function testDeleteWhereMethod()
    {
        $users = factory(UserModel::class, 3)->create();
        $user = $users->random();
        $delete = UserModel::deleteWhere(['id' => $user->id]);
        $users = UserModel::getAll();

        $this->assertCount(2, $users->toArray());
        $this->seeInDatabase('users', ['id' => $user->id]);
        $this->assertTrue($delete === 1);
    }

    public function testDeleteWhereMethodError()
    {
        factory(UserModel::class)->create();
        $test = UserModel::deleteWhere(function () {
            throw new \Exception('testing');
        });
        $this->assertEquals('testing', $test->getMEssage());
    }

    public function testUpdateWhereMethod()
    {
        $users = factory(UserModel::class, 3)->create();
        $user = $users->random();
        $update = UserModel::updateWhere(['id' => $user->id], ['first_name' => 'first', 'last_name' => 'last']);

        $this->assertTrue($update === 1);
        $this->seeInDatabase('users', ['first_name' => 'first', 'last_name' => 'last']);
    }

    public function testUpdateWhereMethodError()
    {
        factory(UserModel::class)->create();
        $test = UserModel::updateWhere(function () {
            throw new \Exception('testing');
        }, ['first_name' => 'first', 'last_name' => 'last']);

        $this->assertEquals('testing', $test->getMEssage());
    }
}
