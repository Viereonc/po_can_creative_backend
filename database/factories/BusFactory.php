<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bus>
 */
class BusFactory extends Factory
{
    public function definition(): array
    {
        return [
            'bus_name' => $this->faker->name(),
            'seat_capacity' => $this->faker->numberBetween(10, 80),
        ];
    }
}
