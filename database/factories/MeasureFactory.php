<?php

namespace Database\Factories;

use App\Models\Feet;
use App\Models\Inch;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeasureFactory extends Factory
{
    public function definition()
    {
        $height = Inch::inRandomOrder()->first();
        $width = Inch::inRandomOrder()->first();
        $length = Feet::inRandomOrder()->first();
        $name = $height->name . '"  x  ' . $width->name . '"  x  ' . $length->name . '\'';

        return [
            'name' => $name,
            'height' => $height->id,
            'width' => $width->id,
            'length' => $length->id,
            'm2' => $width->centimeter * $length->centimeter / 10000,
        ];
    }
}
