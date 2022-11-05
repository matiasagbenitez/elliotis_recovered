<?php

namespace Database\Factories;

use App\Models\Locality;
use Illuminate\Support\Str;
use App\Models\IvaCondition;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    const BUSINESS_NAMES_SUPPLIERS = [
        'Maderera La Loma',
        'Zavalla Moreno SA',
        'Madersat',
        'Maderera Los Tilos',
        'Maderera El Terreno',
        'El Emporio del Terciado',
        'Fenólicos La Plata',
        'Maderera Cedimad',
        'Maderera El Cedrito',
        'Mercoplat SA',
        'Arauco Argentina SA',
        'Forestal Bosques del Plata SA',
        'Forestal Guarani SA',
        'Walti SRL',
        'Forestal Garuhape SA',
        'Foresto Industrial Langer',
        'Centro Forestal Predio YPora AASA',
        'Pindó SA',
    ];

    public function definition()
    {
        $business_name = self::BUSINESS_NAMES_SUPPLIERS[$this->faker->unique()->numberBetween(0, count(self::BUSINESS_NAMES_SUPPLIERS) - 1)];
        $iva_condition_id = IvaCondition::inRandomOrder()->first()->id;
        $locality_id = Locality::inRandomOrder()->first()->id;
        $slug = Str::slug($business_name, '-');
        $email = $slug . '@gmail.com';

        return [
            'business_name' => $business_name,
            'slug' => $slug,
            'iva_condition_id' => $iva_condition_id,
            'cuit' => $this->faker->numberBetween(20, 30) . '-' . $this->faker->numberBetween(10000000, 99999999) . '-' . $this->faker->numberBetween(0, 9),
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'adress' => $this->faker->streetAddress,
            'locality_id' => $locality_id,
            'phone' => $this->faker->phoneNumber,
            'email' => $email,
            'active' => true,
            'observations' => $this->faker->text(100),
        ];
    }
}
