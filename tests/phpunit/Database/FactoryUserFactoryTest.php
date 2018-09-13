<?php
namespace App\Tests\Database;

use App\Models\UserModel;
use App\Tests\TestCase;

/**
 * Class FactoryUserFactoryTest
 *
 * @package App\Tests\Database
 */
class FactoryUserFactoryTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testUserFactory()
    {
        $user = factory(UserModel::class)->make()->toArray();

        $this->assertArrayHasKey('first_name', $user);
        $this->assertArrayHasKey('last_name', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('role', $user);
        $this->assertArrayHasKey('status', $user);
        $this->assertArrayHasKey('api_token', $user);
        $this->assertArrayHasKey('activation_date', $user);
        $this->assertArrayHasKey('last_login_date', $user);

        $this->assertArrayNotHasKey('activation_code', $user);
        $this->assertArrayNotHasKey('password', $user);
    }
}
