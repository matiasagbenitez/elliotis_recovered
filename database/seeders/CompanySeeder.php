<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run()
    {
        $company = [
            'name' => 'CHP e hijos',
            'slogan' => 'Primero los machimbres. Segundo, Francia.',
            'email' => 'chpehijos@gmail.com',
            'phone' => '+5493743445566',
            'address' => 'Av. Aconcagua 1100, Jardín América, Misiones, Argentina',
            'cp' => '3328',
            'logo' => '/img/logo_empresa.png'
        ];

        \App\Models\Company::create($company);
    }
}