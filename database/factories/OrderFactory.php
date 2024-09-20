<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'ticket_id' => Ticket::factory()->create()->ticket_id,
            'total_price' => $this->faker->numberBetween(1000, 10000),
            'status' => $this->faker->randomElement(['pending', 'success', 'cancel']),
        ];
    }
}
