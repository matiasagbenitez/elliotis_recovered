<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    protected $model = User::class;
    CONST NAMES = ['Juan Pérez', 'María González', 'Luisa López', 'Jorge Martínez', 'Ana Sánchez', 'Sofía Hernández', 'Miguel Rodríguez', 'Lucía Díaz', 'Santiago Fernández', 'Valentina Martín', 'David Jiménez', 'Paula García', 'Daniel López', 'Ángela Gómez', 'Javier Martínez', 'Natalia Pérez', 'Álvaro González', 'María Sánchez', 'Jesús Sánchez', 'Sara Martín', 'Miguel Ángel García', 'Sofía Rodríguez', 'Santiago Martínez', 'Lucía Martín', 'David Pérez', 'Paula Martín', 'Daniel González', 'Ángela Martín', 'Javier Sánchez', 'Natalia Gómez', 'Álvaro Martínez', 'María García', 'Jesús García', 'Sara Sánchez', 'Miguel Ángel Martín', 'Sofía Martín', 'Santiago González', 'Lucía González', 'David Martínez', 'Paula Sánchez', 'Daniel Martín', 'Ángela Sánchez', 'Javier García', 'Natalia Martín', 'Álvaro Sánchez', 'María Martín', 'Jesús Martín', 'Sara García', 'Miguel Ángel Sánchez', 'Sofía González', 'Santiago Martín', 'Lucía Martínez', 'David González', 'Paula González', 'Daniel Sánchez', 'Ángela Martínez', 'Javier Martín', 'Natalia González', 'Álvaro García', 'María González', 'Jesús González', 'Sara Martínez', 'Miguel Ángel Martínez', 'Sofía Martínez', 'Santiago Sánchez', 'Lucía Sánchez', 'David Sánchez', 'Paula Martínez', 'Daniel Martínez', 'Ángela González', 'Javier González', 'Natalia Martínez', 'Álvaro Martínez', 'María Martínez', 'Jesús Martínez'];

    public function definition()
    {
        // Get unique name from NAMES array
        $name = $this->faker->unique()->randomElement(self::NAMES);

        return [
            'name' => $name,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function withPersonalTeam()
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()
                ->state(function (array $attributes, User $user) {
                    return ['name' => $user->name.'\'s Team', 'user_id' => $user->id, 'personal_team' => true];
                }),
            'ownedTeams'
        );
    }
}
