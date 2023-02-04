<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Matías Benítez',
            'email' => 'matias@correo.com',
            'password' => bcrypt('password')
        ])->assignRole('sudo');

        User::create([
            'name' => 'Auditor',
            'email' => 'auditor@correo.com',
            'password' => bcrypt('password')
        ])->assignRole('auditor');

        User::create([
            'name' => 'Admin',
            'email' => 'admin@correo.com',
            'password' => bcrypt('password')
        ])->assignRole('admin');

        User::create([
            'name' => 'Empleado',
            'email' => 'empleado@correo.com',
            'password' => bcrypt('password')
        ])->assignRole('employee');

        User::factory(5)->create();
    }
}
