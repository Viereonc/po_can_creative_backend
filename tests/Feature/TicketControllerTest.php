<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Destination;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        return $user;
    }

    /** @test */
    public function get_all_tickets()
    {
        $this->authenticateUser();

        Ticket::factory()->count(3)->create();

        $response = $this->getJson('/api/tickets');
        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function show_specific_ticket()
    {
        $this->authenticateUser();

        $ticket = Ticket::factory()->create();

        $response = $this->getJson("/api/tickets/{$ticket->ticket_id}");
        $response->assertStatus(200)
                 ->assertJson(['ticket_id' => $ticket->ticket_id]);
    }

    /** @test */
    public function post_ticket_data()
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $destination = Destination::factory()->create();

        $data = [
            'user_id' => $user->id,
            'destination_id' => $destination->destination_id,
            'seat_number' => 5,
            'price' => 10000,
        ];

        $response = $this->postJson('/api/tickets/post', $data);
        $response->assertStatus(201)
                 ->assertJson($data);
    }

    /** @test */
    public function update_ticket_data()
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $ticket = Ticket::factory()->create();

        $data = [
            'user_id' => $user->id,
            'destination_id' => $ticket->destination_id,
            'seat_number' => 10,
            'price' => 15000,
        ];

        $response = $this->putJson("/api/tickets/put/{$ticket->ticket_id}", $data);
        $response->assertStatus(200)
                 ->assertJson(['seat_number' => 10, 'price' => 15000]);
    }

    /** @test */
    public function delete_ticket_data()
    {
        $this->authenticateUser();

        $ticket = Ticket::factory()->create();

        $response = $this->deleteJson("/api/tickets/delete/{$ticket->ticket_id}");
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Ticket deleted successfully']);
    }

    /** @test */
    public function get_tickets_for_destination()
    {
        $this->authenticateUser();

        $destination = Destination::factory()->create();

        Ticket::factory()->create(['destination_id' => $destination->destination_id]);

        $response = $this->getJson("/api/tickets/destination/{$destination->destination_id}");
        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    /** @test */
    public function get_tickets_with_price_above()
    {
        $this->authenticateUser();

        Ticket::factory()->create(['price' => 20000]);

        Ticket::factory()->create(['price' => 5000]);

        $response = $this->getJson('/api/tickets/price-above/10000');
        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }
}
