<?php
namespace Database\Seeds;

use Illuminate\Database\Seeder;

/**
 * Class ApplicationSeeder
 *
 * @package Database\Seeds
 */
class ApplicationSeeder extends Seeder
{
    public function run()
    {
        if (app()->environment() == 'production') {
            $this->call(\Database\Seeds\Production\UsersTableSeeder::class);
        } else {
            $this->call(\Database\Seeds\Development\UsersTableSeeder::class);
        }
    }
}
