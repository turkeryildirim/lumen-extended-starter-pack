<?php

namespace Database\Seeds\Production;

use App\Constants\UserRoleConstant;
use App\Models\UserModel;
use Illuminate\Database\Seeder;

/**
 * Class UsersTableSeeder
 *
 * @package Database\Seeds\Production
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
    }
}
