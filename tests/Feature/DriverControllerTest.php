<?php

namespace Tests\Feature;

use App\Models\Driver;
use App\Models\User;
use App\Models\Bus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DriverControllerTest extends TestCase
{

    protected function authenticateUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        return $user;
    }

    /** @test */
    public function get_all_drivers()
    {
        $this->authenticateUser();

        Driver::factory()->count(3)->create();

        $response = $this->getJson('/api/drivers');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function show_specific_driver()
    {
        $this->authenticateUser();

        $driver = Driver::factory()->create();

        $response = $this->getJson("/api/drivers/{$driver->driver_id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'driver_id' => $driver->driver_id,
                     'license_number' => $driver->license_number,
                 ]);
    }

    /** @test */
    public function post_driver_data()
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $bus = Bus::factory()->create();

        $data = [
            'user_id' => $user->id,
            'bus_id' => $bus->bus_id,
            'license_number' => 'ABC1234567',
        ];

        $response = $this->postJson('/api/drivers/post', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'license_number' => 'ABC1234567',
                 ]);
    }

    /** @test */
    public function update_driver_data()
    {
        $this->authenticateUser();

        $driver = Driver::factory()->create();

        $data = [
            'user_id' => $driver->user_id,
            'bus_id' => $driver->bus_id,
            'license_number' => 'XYZ9876543',
        ];

        $response = $this->putJson("/api/drivers/put/{$driver->driver_id}", $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'license_number' => 'XYZ9876543',
                 ]);
    }

    /** @test */
    public function delete_driver_data()
    {
        $this->authenticateUser();

        $driver = Driver::factory()->create();

        $response = $this->deleteJson("/api/drivers/delete/{$driver->driver_id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Driver deleted successfully']);
    }

    /** @test */
    public function get_drivers_by_bus()
    {
        $this->authenticateUser();

        $bus = Bus::factory()->create();
        $drivers = Driver::factory()->count(3)->create(['bus_id' => $bus->bus_id]);

        $response = $this->getJson("/api/drivers/by-bus/{$bus->bus_id}");

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function get_drivers_by_user()
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $drivers = Driver::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/drivers/by-user/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }
}
