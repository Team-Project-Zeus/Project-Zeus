<?php

use App\Appointment;
use Illuminate\Database\Seeder;

class AppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Appointment::create([
            'name'     => 'default',
            'email'    => 'default@gmail.com',
            'password' => bcrypt('12345678'),
            'user_role'    => 'default',
        ]);
    }
}
