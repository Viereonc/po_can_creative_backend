<?php

namespace Database\Factories;

use App\Models\Bus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Destination>
 */
class DestinationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'bus_id' => Bus::factory()->create()->bus_id,
            'start_location' => $this->faker->city(),
            'end_location' => $this->faker->city(),
            'departure_time' => now()->addDays(5)->format('Y-m-d H:i:s'),
        ];
    }
}
