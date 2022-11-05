<?php

namespace Database\Seeders;

use App\Models\Measure;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MachimbreMeasureSeeder extends Seeder
{
    public function run()
    {
        $measures = [
            [
                'name' => '1/2" x 4" x 10\'',
                'favorite' => true,
                'is_trunk' => false,
                'height' => 2,
                'width' => 16,
                'length' => 40,
                'm2' => 0.30
            ],
            [
                'name' => '1/2" x 4" x 11\'',
                'favorite' => true,
                'is_trunk' => false,
                'height' => 2,
                'width' => 16,
                'length' => 44,
                'm2' => 0.34
            ],
            [
                'name' => '1/2" x 4" x 12\'',
                'favorite' => true,
                'is_trunk' => false,
                'height' => 2,
                'width' => 16,
                'length' => 48,
                'm2' => 0.37
            ],
            [
                'name' => '1/2" x 4" x 13\'',
                'favorite' => true,
                'is_trunk' => false,
                'height' => 2,
                'width' => 16,
                'length' => 52,
                'm2' => 0.40
            ],
        ];

        foreach ($measures as $measure) {
            Measure::create($measure);
        }
    }
}
