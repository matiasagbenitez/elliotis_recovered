<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProvinceFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->state()
        ];
    }
}
