<?php

namespace Database\Seeds\Development;

use Illuminate\Database\Seeder;
use App\Constants\UserRoleConstant;
use App\Models\UserModel;

/**
 * Class UsersTableSeeder
 *
 * @package Database\Seeds\Development
 */
class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // admin
        factory(UserModel::class)->create([
            'email' => 'admin@test.com',
            'role' => UserRoleConstant::ADMIN
        ]);

        // user
        factory(UserModel::class)->create([
            'email' => 'user@test.com',
            'role' => UserRoleConstant::USER
        ]);
    }
}
