<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'     => 'default',
            'email'    => 'default@gmail.com',
            'password' => bcrypt('12345678'),
            'user_role'    => 'default',
        ]);

        User::create([
            'name'     => 'student',
            'email'    => 'student@gmail.com',
            'password' => bcrypt('12345678'),
            'user_role'    => 'student',
        ]);

        User::create([
            'name'     => 'driving instructor',
            'email'    => 'instructor@gmail.com',
            'password' => bcrypt('12345678'),
            'user_role'    => 'driving_instructor',
        ]);

        User::create([
            'name'     => 'admin',
            'email'    => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'user_role'    => 'admin',
        ]);
    }
}
