<?php

namespace Database\Seeders;

use App\Models\IvaCondition;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IvaConditionSeeder extends Seeder
{
    public function run()
    {
        $ivaConditions = [
            [
                'name' => 'IVA Responsable Inscripto',
                'discriminate' => true,
            ],
            [
                'name' => 'IVA Sujeto Exento',
                'discriminate' => false,
            ],
            [
                'name' => 'Monotributista',
                'discriminate' => false,
            ],
            [
                'name' => 'Consumidor Final',
                'discriminate' => false,
            ]
        ];

        foreach ($ivaConditions as $ivaCondition) {
            IvaCondition::create($ivaCondition);
        }
    }
}
