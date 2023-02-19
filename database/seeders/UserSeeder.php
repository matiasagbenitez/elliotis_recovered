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
            'password' => bcrypt('password'),
        ])->assignRole('sudo');

        User::create([
            'name' => 'Auditor José Hernández',
            'company_id' => 1,
            'email' => 'jose@correo.com',
            'password' => bcrypt('password')
        ])->assignRole('auditor');

        User::create([
            'name' => 'Ale Pujalski',
            'company_id' => 1,
            'email' => 'ale@correo.com',
            'password' => bcrypt('password')
        ])->assignRole('administrador');

        User::create([
            'name' => 'Victor Benítez',
            'company_id' => 1,
            'email' => 'victor@correo.com',
            'password' => bcrypt('password')
        ])->assignRole('administrador');

        User::create([
            'name' => 'Darío Benedetto',
            'company_id' => 1,
            'email' => 'dario@correo.com',
            'password' => bcrypt('password')
        ])->assignRole('empleado');

        User::create([
            'name' => 'Lucas Pratto',
            'company_id' => 1,
            'email' => 'lucas@correo.com',
            'password' => bcrypt('password')
        ])->assignRole('empleado');

        User::create([
            'name' => 'Juan Fernando Quintero',
            'company_id' => 1,
            'email' => 'juan@correo.com',
            'password' => bcrypt('password')
        ])->assignRole('empleado');

        User::create([
            'name' => 'Gonzalo Martínez',
            'company_id' => 1,
            'email' => 'gonzalo@correo.com',
            'password' => bcrypt('password')
        ])->assignRole('empleado');
    }
}
