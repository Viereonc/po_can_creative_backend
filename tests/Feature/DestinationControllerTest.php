<?php

namespace Tests\Feature;

use App\Models\Destination;
use App\Models\Bus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestinationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        return $user;
    }

    /** @test */
    public function get_all_destinations()
    {
        $this->authenticateUser();

        Destination::factory()->count(3)->create();

        $response = $this->getJson('/api/destinations');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function show_spesific_destination()
    {
        $this->authenticateUser();

        $destination = Destination::factory()->create();

        $response = $this->getJson("/api/destinations/{$destination->destination_id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'destination_id' => $destination->destination_id,
                     'start_location' => $destination->start_location,
                     'end_location' => $destination->end_location,
                 ]);
    }

    /** @test */
    public function post_destination_data()
    {
        $this->authenticateUser();

        $bus = Bus::factory()->create();

        $data = [
            'bus_id' => $bus->bus_id,
            'start_location' => 'City A',
            'end_location' => 'City B',
            'departure_time' => now()->addDays(5)->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson('/api/destinations/post', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'start_location' => 'City A',
                     'end_location' => 'City B',
                 ]);
    }

    /** @test */
    public function update_destination_data()
    {
        $this->authenticateUser();

        $destination = Destination::factory()->create();

        $data = [
            'bus_id' => $destination->bus_id,
            'start_location' => 'Updated City A',
            'end_location' => 'Updated City B',
            'departure_time' => now()->addDays(5)->format('Y-m-d H:i:s'),
        ];

        $response = $this->putJson("/api/destinations/put/{$destination->destination_id}", $data);

        $response->assertStatus(200)
                ->assertJson([
                    'start_location' => 'Updated City A',
                    'end_location' => 'Updated City B',
                    'bus_id' => $destination->bus_id,
                ]);
    }

    /** @test */
    public function delete_destination_data()
    {
        $this->authenticateUser();

        $destination = Destination::factory()->create();

        $response = $this->deleteJson("/api/destinations/delete/{$destination->destination_id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Destination deleted successfully']);
    }

    /** @test */
    public function it_can_check_if_destination_has_departed()
    {
        $this->authenticateUser();

        $destination = Destination::factory()->create(['departure_time' => now()->subHour()]);

        $response = $this->getJson("/api/destinations/{$destination->destination_id}/check-departed");

        $response->assertStatus(200)
                ->assertJson([
                    'destination' => $destination->start_location . ' - ' . $destination->end_location,
                    'has_departed' => true,
                    'departure_time' => $destination->getDepartureTime(),
                ]);
    }

   /** @test */
    public function it_can_check_if_destination_has_not_departed()
    {
        $this->authenticateUser();

        $destination = Destination::factory()->create(['departure_time' => now()->addHour()]);

        $response = $this->getJson("/api/destinations/{$destination->destination_id}/check-not-departed");

        $response->assertStatus(200)
                ->assertJson([
                    'destination' => $destination->start_location . ' - ' . $destination->end_location,
                    'has_not_departed' => true,
                    'departure_time' => $destination->getDepartureTime(),
                ]);
    }
}
