<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'bus_id' => Bus::factory()->create()->bus_id,
            'license_number' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
        ];
    }
}
