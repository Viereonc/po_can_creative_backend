<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BusControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        return $user;
    }

    /** @test */
    public function get_all_buses()
    {
        $this->authenticateUser();

        Bus::factory()->count(3)->create();

        $response = $this->getJson('/api/buses');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function show_spesific_bus()
    {
        $this->authenticateUser();

        $bus = Bus::factory()->create();

        $response = $this->getJson("/api/buses/{$bus->bus_id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'bus_id' => $bus->bus_id,
                     'bus_name' => $bus->bus_name
                 ]);
    }

    /** @test */
    public function post_bus_data()
    {
        $this->authenticateUser();

        $data = [
            'bus_name' => 'PO CAN Travel',
            'seat_capacity' => 50,
        ];

        $response = $this->postJson('/api/buses/post', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'bus_name' => 'PO CAN Travel',
                     'seat_capacity' => 50
                 ]);
    }

    /** @test */
    public function update_bus_data()
    {
        $this->authenticateUser();

        $bus = Bus::factory()->create();

        $data = [
            'bus_name' => 'Updated Bus Name',
            'seat_capacity' => 60,
        ];

        $response = $this->putJson("/api/buses/put/{$bus->bus_id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'bus_name' => 'Updated Bus Name',
                     'seat_capacity' => 60
                 ]);
    }

    /** @test */
    public function delete_bus_data()
    {
        $this->authenticateUser();

        $bus = Bus::factory()->create();

        $response = $this->deleteJson("/api/buses/delete/{$bus->bus_id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Bus deleted successfully']);
    }

    /** @test */
    public function fetch_bus_drivers()
    {
        $this->authenticateUser();

        $bus = Bus::factory()->create();

        $response = $this->getJson("/api/buses/{$bus->bus_id}/drivers");

        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_can_fetch_buses_with_capacity_above()
    {
        $this->authenticateUser();

        Bus::factory()->create(['seat_capacity' => 30]);
        Bus::factory()->create(['seat_capacity' => 50]);

        $response = $this->getJson('/api/buses/capacity-above/40');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }
}
