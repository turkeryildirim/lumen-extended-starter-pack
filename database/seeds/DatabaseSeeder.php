<?php
// @codingStandardsIgnoreFile

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(\Database\Seeds\ApplicationSeeder::class);
    }
}
