<?php

namespace Database\Seeders;

use App\Models\Measure;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TrunkMeasureSeeder extends Seeder
{
    public function run()
    {
        $measures = [
            [
                'name' => '10\'',
                'favorite' => true,
                'is_trunk' => true,
                'height' => null,
                'width' => null,
                'length' => 40,
            ],
            [
                'name' => '11\'',
                'favorite' => true,
                'is_trunk' => true,
                'height' => null,
                'width' => null,
                'length' => 44,
            ],
            [
                'name' => '12\'',
                'favorite' => true,
                'is_trunk' => true,
                'height' => null,
                'width' => null,
                'length' => 48,
            ],
            [
                'name' => '13\'',
                'favorite' => true,
                'is_trunk' => true,
                'height' => null,
                'width' => null,
                'length' => 52,
            ],
        ];

        foreach ($measures as $measure) {
            $measure = Measure::create($measure);
        }
    }
}
