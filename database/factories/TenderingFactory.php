<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenderingFactory extends Factory
{
    public function definition()
    {
        $user_id = User::inRandomOrder()->first()->id;
        $start_date = $this->faker->dateTimeBetween('-3 day', 'now');
        $end_date = $this->faker->dateTimeBetween($start_date, '+3 day');

        return [
            'user_id' => $user_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'subtotal' => 0,
            'iva' => 0,
            'total' => 0,
            'created_at' => $start_date,
            'updated_at' => $start_date,
        ];
    }
}
