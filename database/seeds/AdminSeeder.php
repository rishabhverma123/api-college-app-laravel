<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new \App\Admin();
        $admin->name="Shashank Singh";
        $admin->rollno='1404310047';
        $admin->username="shashank";
        $admin->password=bcrypt("hacker");
        $admin->save();

        $admin = new \App\Admin();
        $admin->name="Rachit Agarwal";
        $admin->rollno='1404310035';
        $admin->username="rachit";
        $admin->password=bcrypt("secret");
        $admin->save();

        $admin = new \App\Admin();
        $admin->name="Rishabh Singh";
        $admin->rollno='1404310036';
        $admin->username="rishabh";
        $admin->password=bcrypt("secret");
        $admin->save();
    }
}
