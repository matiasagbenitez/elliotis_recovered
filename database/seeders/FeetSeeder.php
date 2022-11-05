<?php

namespace Database\Seeders;

use App\Models\Feet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FeetSeeder extends Seeder
{
    public function run()
    {
        $feets = [
            [
                'name' => '1/4',
                'value' => 0.25,
                'centimeter' => 7.62
            ],
            [
                'name' => '1/2',
                'value' => 0.5,
                'centimeter' => 15.24
            ],
            [
                'name' => '3/4',
                'value' => 0.75,
                'centimeter' => 22.86
            ],
            [
                'name' => '1',
                'value' => 1,
                'centimeter' => 30.48
            ],
            [
                'name' => '1 1/4',
                'value' => 1.25,
                'centimeter' => 38.1
            ],
            [
                'name' => '1 1/2',
                'value' => 1.5,
                'centimeter' => 45.72
            ],
            [
                'name' => '1 3/4',
                'value' => 1.75,
                'centimeter' => 53.34
            ],
            [
                'name' => '2',
                'value' => 2,
                'centimeter' => 60.96
            ],
            [
                'name' => '2 1/4',
                'value' => 2.25,
                'centimeter' => 68.58
            ],
            [
                'name' => '2 1/2',
                'value' => 2.5,
                'centimeter' => 76.2
            ],
            [
                'name' => '2 3/4',
                'value' => 2.75,
                'centimeter' => 83.82
            ],
            [
                'name' => '3',
                'value' => 3,
                'centimeter' => 91.44
            ],
            [
                'name' => '3 1/4',
                'value' => 3.25,
                'centimeter' => 99.06
            ],
            [
                'name' => '3 1/2',
                'value' => 3.5,
                'centimeter' => 106.68
            ],
            [
                'name' => '3 3/4',
                'value' => 3.75,
                'centimeter' => 114.3
            ],
            [
                'name' => '4',
                'value' => 4,
                'centimeter' => 121.92
            ],
            [
                'name' => '4 1/4',
                'value' => 4.25,
                'centimeter' => 129.54
            ],
            [
                'name' => '4 1/2',
                'value' => 4.5,
                'centimeter' => 137.16
            ],
            [
                'name' => '4 3/4',
                'value' => 4.75,
                'centimeter' => 144.78
            ],
            [
                'name' => '5',
                'value' => 5,
                'centimeter' => 152.4
            ],
            [
                'name' => '5 1/4',
                'value' => 5.25,
                'centimeter' => 160.02
            ],
            [
                'name' => '5 1/2',
                'value' => 5.5,
                'centimeter' => 167.64
            ],
            [
                'name' => '5 3/4',
                'value' => 5.75,
                'centimeter' => 175.26
            ],
            [
                'name' => '6',
                'value' => 6,
                'centimeter' => 182.88
            ],
            [
                'name' => '6 1/4',
                'value' => 6.25,
                'centimeter' => 190.5
            ],
            [
                'name' => '6 1/2',
                'value' => 6.5,
                'centimeter' => 198.12
            ],
            [
                'name' => '6 3/4',
                'value' => 6.75,
                'centimeter' => 205.74
            ],
            [
                'name' => '7',
                'value' => 7,
                'centimeter' => 213.36
            ],
            [
                'name' => '7 1/4',
                'value' => 7.25,
                'centimeter' => 220.98
            ],
            [
                'name' => '7 1/2',
                'value' => 7.5,
                'centimeter' => 228.6
            ],
            [
                'name' => '7 3/4',
                'value' => 7.75,
                'centimeter' => 236.22
            ],
            [
                'name' => '8',
                'value' => 8,
                'centimeter' => 243.84
            ],
            [
                'name' => '8 1/4',
                'value' => 8.25,
                'centimeter' => 251.46
            ],
            [
                'name' => '8 1/2',
                'value' => 8.5,
                'centimeter' => 259.08
            ],
            [
                'name' => '8 3/4',
                'value' => 8.75,
                'centimeter' => 266.7
            ],
            [
                'name' => '9',
                'value' => 9,
                'centimeter' => 274.32
            ],
            [
                'name' => '9 1/4',
                'value' => 9.25,
                'centimeter' => 281.94
            ],
            [
                'name' => '9 1/2',
                'value' => 9.5,
                'centimeter' => 289.56
            ],
            [
                'name' => '9 3/4',
                'value' => 9.75,
                'centimeter' => 297.18
            ],
            [
                'name' => '10',
                'value' => 10,
                'centimeter' => 304.8
            ],
            [
                'name' => '10 1/4',
                'value' => 10.25,
                'centimeter' => 312.42
            ],
            [
                'name' => '10 1/2',
                'value' => 10.5,
                'centimeter' => 320.04
            ],
            [
                'name' => '10 3/4',
                'value' => 10.75,
                'centimeter' => 327.66
            ],
            [
                'name' => '11',
                'value' => 11,
                'centimeter' => 335.28
            ],
            [
                'name' => '11 1/4',
                'value' => 11.25,
                'centimeter' => 342.9
            ],
            [
                'name' => '11 1/2',
                'value' => 11.5,
                'centimeter' => 350.52
            ],
            [
                'name' => '11 3/4',
                'value' => 11.75,
                'centimeter' => 358.14
            ],
            [
                'name' => '12',
                'value' => 12,
                'centimeter' => 365.76
            ],
            [
                'name' => '12 1/4',
                'value' => 12.25,
                'centimeter' => 373.38
            ],
            [
                'name' => '12 1/2',
                'value' => 12.5,
                'centimeter' => 381
            ],
            [
                'name' => '12 3/4',
                'value' => 12.75,
                'centimeter' => 388.62
            ],
            [
                'name' => '13',
                'value' => 13,
                'centimeter' => 396.24
            ],
            [
                'name' => '13 1/4',
                'value' => 13.25,
                'centimeter' => 403.86
            ],
            [
                'name' => '13 1/2',
                'value' => 13.5,
                'centimeter' => 411.48
            ],
            [
                'name' => '13 3/4',
                'value' => 13.75,
                'centimeter' => 419.1
            ],
            [
                'name' => '14',
                'value' => 14,
                'centimeter' => 426.72
            ],
            [
                'name' => '14 1/4',
                'value' => 14.25,
                'centimeter' => 434.34
            ],
            [
                'name' => '14 1/2',
                'value' => 14.5,
                'centimeter' => 441.96
            ],
            [
                'name' => '14 3/4',
                'value' => 14.75,
                'centimeter' => 449.58
            ],
            [
                'name' => '15',
                'value' => 15,
                'centimeter' => 457.2
            ],
            [
                'name' => '15 1/4',
                'value' => 15.25,
                'centimeter' => 464.82
            ],
            [
                'name' => '15 1/2',
                'value' => 15.5,
                'centimeter' => 472.44
            ],
            [
                'name' => '15 3/4',
                'value' => 15.75,
                'centimeter' => 480.06
            ],
            [
                'name' => '16',
                'value' => 16,
                'centimeter' => 487.68
            ],
            [
                'name' => '16 1/4',
                'value' => 16.25,
                'centimeter' => 495.3
            ],
            [
                'name' => '16 1/2',
                'value' => 16.5,
                'centimeter' => 502.92
            ],
            [
                'name' => '16 3/4',
                'value' => 16.75,
                'centimeter' => 510.54
            ],
            [
                'name' => '17',
                'value' => 17,
                'centimeter' => 518.16
            ],
            [
                'name' => '17 1/4',
                'value' => 17.25,
                'centimeter' => 525.78
            ],
            [
                'name' => '17 1/2',
                'value' => 17.5,
                'centimeter' => 533.4
            ],
            [
                'name' => '17 3/4',
                'value' => 17.75,
                'centimeter' => 541.02
            ],
            [
                'name' => '18',
                'value' => 18,
                'centimeter' => 548.64
            ],
            [
                'name' => '18 1/4',
                'value' => 18.25,
                'centimeter' => 556.26
            ],
            [
                'name' => '18 1/2',
                'value' => 18.5,
                'centimeter' => 563.88
            ],
            [
                'name' => '18 3/4',
                'value' => 18.75,
                'centimeter' => 571.5
            ],
            [
                'name' => '19',
                'value' => 19,
                'centimeter' => 579.12
            ],
            [
                'name' => '19 1/4',
                'value' => 19.25,
                'centimeter' => 586.74
            ],
            [
                'name' => '19 1/2',
                'value' => 19.5,
                'centimeter' => 594.36
            ],
            [
                'name' => '19 3/4',
                'value' => 19.75,
                'centimeter' => 601.98
            ],
            [
                'name' => '20',
                'value' => 20,
                'centimeter' => 609.6
            ],
        ];

        foreach ($feets as $feet) {
            Feet::create($feet);
        }
    }
}
