<?php
namespace App\Tests\Database;

use App\Models\UserMetaModel;
use App\Models\UserModel;
use App\Tests\TestCase;
use Database\Seeds\Development\UsersTableSeeder;

/**
 * Class SeedDevelopmentUsersTableSeederTest
 *
 * @package App\Tests\Database
 */
class SeedDevelopmentUsersTableSeederTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testUsersTableSeeder()
    {
        $users = UserModel::all();
        $this->assertCount(0, $users);

        $seed = new UsersTableSeeder();
        $seed->run();
        $users = UserModel::all();
        $users_meta = UserMetaModel::all();

        $this->seeInDatabase('users', ['id' => 1]);
        $this->seeInDatabase('users', ['id' => 2]);
        $this->assertCount(2, $users);
        $this->assertCount(2, $users_meta);
    }
}
