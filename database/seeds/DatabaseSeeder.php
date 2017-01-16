<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // $this->call(UserSeeder::class);
        $this->call(AdminSeeder::class); //seed only all admins have been registered on the app
    }
}
