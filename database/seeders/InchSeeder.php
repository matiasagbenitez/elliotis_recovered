<?php

namespace Database\Seeders;

use App\Models\Inch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InchSeeder extends Seeder
{
    public function run()
    {
        $inches = [
            [
                'name' => '1/4',
                'value' => 0.25,
                'centimeter' => 0.635,
            ],
            [
                'name' => '1/2',
                'value' => 0.5,
                'centimeter' => 1.27,
            ],
            [
                'name' => '3/4',
                'value' => 0.75,
                'centimeter' => 1.905,
            ],
            [
                'name' => '1',
                'value' => 1,
                'centimeter' => 2.54,
            ],
            [
                'name' => '1 1/4',
                'value' => 1.25,
                'centimeter' => 3.175,
            ],
            [
                'name' => '1 1/2',
                'value' => 1.5,
                'centimeter' => 3.81,
            ],
            [
                'name' => '1 3/4',
                'value' => 1.75,
                'centimeter' => 4.445,
            ],
            [
                'name' => '2',
                'value' => 2,
                'centimeter' => 5.08,
            ],
            [
                'name' => '2 1/4',
                'value' => 2.25,
                'centimeter' => 5.715,
            ],
            [
                'name' => '2 1/2',
                'value' => 2.5,
                'centimeter' => 6.35,
            ],
            [
                'name' => '2 3/4',
                'value' => 2.75,
                'centimeter' => 6.985,
            ],
            [
                'name' => '3',
                'value' => 3,
                'centimeter' => 7.62,
            ],
            [
                'name' => '3 1/4',
                'value' => 3.25,
                'centimeter' => 8.255,
            ],
            [
                'name' => '3 1/2',
                'value' => 3.5,
                'centimeter' => 8.89,
            ],
            [
                'name' => '3 3/4',
                'value' => 3.75,
                'centimeter' => 9.525,
            ],
            [
                'name' => '4',
                'value' => 4,
                'centimeter' => 10.16,
            ],
            [
                'name' => '4 1/4',
                'value' => 4.25,
                'centimeter' => 10.795,
            ],
            [
                'name' => '4 1/2',
                'value' => 4.5,
                'centimeter' => 11.43,
            ],
            [
                'name' => '4 3/4',
                'value' => 4.75,
                'centimeter' => 12.065,
            ],
            [
                'name' => '5',
                'value' => 5,
                'centimeter' => 12.7,
            ],
            [
                'name' => '5 1/4',
                'value' => 5.25,
                'centimeter' => 13.335,
            ],
            [
                'name' => '5 1/2',
                'value' => 5.5,
                'centimeter' => 13.97,
            ],
            [
                'name' => '5 3/4',
                'value' => 5.75,
                'centimeter' => 14.605,
            ],
            [
                'name' => '6',
                'value' => 6,
                'centimeter' => 15.24,
            ],
            [
                'name' => '6 1/4',
                'value' => 6.25,
                'centimeter' => 15.875,
            ],
            [
                'name' => '6 1/2',
                'value' => 6.5,
                'centimeter' => 16.51,
            ],
            [
                'name' => '6 3/4',
                'value' => 6.75,
                'centimeter' => 17.145,
            ],
            [
                'name' => '7',
                'value' => 7,
                'centimeter' => 17.78,
            ],
            [
                'name' => '7 1/4',
                'value' => 7.25,
                'centimeter' => 18.415,
            ],
            [
                'name' => '7 1/2',
                'value' => 7.5,
                'centimeter' => 19.05,
            ],
            [
                'name' => '7 3/4',
                'value' => 7.75,
                'centimeter' => 19.685,
            ],
            [
                'name' => '8',
                'value' => 8,
                'centimeter' => 20.32,
            ],
            [
                'name' => '8 1/4',
                'value' => 8.25,
                'centimeter' => 20.955,
            ],
            [
                'name' => '8 1/2',
                'value' => 8.5,
                'centimeter' => 21.59,
            ],
            [
                'name' => '8 3/4',
                'value' => 8.75,
                'centimeter' => 22.225,
            ],
            [
                'name' => '9',
                'value' => 9,
                'centimeter' => 22.86,
            ],
            [
                'name' => '9 1/4',
                'value' => 9.25,
                'centimeter' => 23.495,
            ],
            [
                'name' => '9 1/2',
                'value' => 9.5,
                'centimeter' => 24.13,
            ],
            [
                'name' => '9 3/4',
                'value' => 9.75,
                'centimeter' => 24.765,
            ],
            [
                'name' => '10',
                'value' => 10,
                'centimeter' => 25.4,
            ],
            [
                'name' => '10 1/4',
                'value' => 10.25,
                'centimeter' => 26.035,
            ],
            [
                'name' => '10 1/2',
                'value' => 10.5,
                'centimeter' => 26.67,
            ],
            [
                'name' => '10 3/4',
                'value' => 10.75,
                'centimeter' => 27.305,
            ],
            [
                'name' => '11',
                'value' => 11,
                'centimeter' => 27.94,
            ],
            [
                'name' => '11 1/4',
                'value' => 11.25,
                'centimeter' => 28.575,
            ],
            [
                'name' => '11 1/2',
                'value' => 11.5,
                'centimeter' => 29.21,
            ],
            [
                'name' => '11 3/4',
                'value' => 11.75,
                'centimeter' => 29.845,
            ],
            [
                'name' => '12',
                'value' => 12,
                'centimeter' => 30.48,
            ],
            [
                'name' => '12 1/4',
                'value' => 12.25,
                'centimeter' => 31.115,
            ],
            [
                'name' => '12 1/2',
                'value' => 12.5,
                'centimeter' => 31.75,
            ],
            [
                'name' => '12 3/4',
                'value' => 12.75,
                'centimeter' => 32.385,
            ],
            [
                'name' => '13',
                'value' => 13,
                'centimeter' => 33.02,
            ],
            [
                'name' => '13 1/4',
                'value' => 13.25,
                'centimeter' => 33.655,
            ],
            [
                'name' => '13 1/2',
                'value' => 13.5,
                'centimeter' => 34.29,
            ],
            [
                'name' => '13 3/4',
                'value' => 13.75,
                'centimeter' => 34.925,
            ],
            [
                'name' => '14',
                'value' => 14,
                'centimeter' => 35.56,
            ],
            [
                'name' => '14 1/4',
                'value' => 14.25,
                'centimeter' => 36.195,
            ],
            [
                'name' => '14 1/2',
                'value' => 14.5,
                'centimeter' => 36.83,
            ],
            [
                'name' => '14 3/4',
                'value' => 14.75,
                'centimeter' => 37.465,
            ],
            [
                'name' => '15',
                'value' => 15,
                'centimeter' => 38.1,
            ]
        ];

        foreach ($inches as $inch) {
           Inch::create($inch);
        }
    }
}
