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
        //this command is meant for a seeder
        $this->call(
            UsersSeeder::class,
            AppointmentsSeeder::class,
        );

        //this command is meant for a factory
//        factory(App\appointments::class, 50)->create();
    }
}