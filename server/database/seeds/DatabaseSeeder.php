<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(MembershipTypeSeeder::class);
        $this->call(PropertySeeder::class);
        $this->call(PropertyMembershipTypeSeeder::class);
    }
}
