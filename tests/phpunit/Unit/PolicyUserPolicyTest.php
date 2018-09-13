<?php
namespace App\Tests\Unit;

use App\Constants\UserRoleConstant;
use App\Models\UserModel;
use App\Policies\UserPolicy;
use App\Tests\TestCase;

/**
 * Class PolicyUserPolicyTest
 *
 * @package App\Tests\Unit
 */
class PolicyUserPolicyTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testBeforeMethod()
    {
        $admin_user = factory(UserModel::class)->create([
            'role' => UserRoleConstant::ADMIN
        ]);
        $user = factory(UserModel::class)->create([
            'role' => UserRoleConstant::USER
        ]);
        $policy = new UserPolicy();

        $this->assertTrue($policy->before($admin_user, 'show') === true);
        $this->assertTrue($policy->before($admin_user, 'delete') !== true);
        $this->assertTrue($policy->before($user, 'show') !== true);
        $this->assertTrue($policy->before($user, 'delete') !== true);
    }

    public function testGetMethod()
    {
        $user = factory(UserModel::class)->create();
        $policy = new UserPolicy();

        $this->assertTrue($policy->get($user) === false);
    }

    public function testShowMethod()
    {
        $user1 = factory(UserModel::class)->create();
        $user2 = factory(UserModel::class)->create();
        $policy = new UserPolicy();

        $this->assertTrue($policy->show($user1, $user2) === false);
        $this->assertTrue($policy->show($user1, $user1) === true);
    }

    public function testCreateMethod()
    {
        $user = factory(UserModel::class)->create();
        $policy = new UserPolicy();

        $this->assertTrue($policy->create($user) === false);
    }

    public function testUpdateMethod()
    {
        $user1 = factory(UserModel::class)->create();
        $user2 = factory(UserModel::class)->create();
        $policy = new UserPolicy();

        $this->assertTrue($policy->update($user1, $user2->id) === false);
        $this->assertTrue($policy->update($user1, $user1->id) === true);
    }

    public function testDeleteMethod()
    {
        $user1 = factory(UserModel::class)->create([
            'role' => UserRoleConstant::USER
        ]);
        $user2 = factory(UserModel::class)->create([
            'role' => UserRoleConstant::ADMIN
        ]);
        $policy = new UserPolicy();

        $this->assertTrue($policy->delete($user1, 55) === false);
        $this->assertTrue($policy->delete($user2, $user2->id) === false);
        $this->assertTrue($policy->delete($user2, 55) === true);
    }
}
