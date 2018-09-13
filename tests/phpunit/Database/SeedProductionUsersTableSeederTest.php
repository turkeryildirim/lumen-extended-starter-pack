<?php
namespace App\Tests\Database;

use App\Models\UserMetaModel;
use App\Models\UserModel;
use App\Tests\TestCase;
use Database\Seeds\Production\UsersTableSeeder;

/**
 * Class SeedProductionUsersTableSeederTest
 *
 * @package App\Tests\Database
 */
class SeedProductionUsersTableSeederTest extends TestCase
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
        $this->assertCount(1, $users);
        $this->assertCount(1, $users_meta);
    }
}
