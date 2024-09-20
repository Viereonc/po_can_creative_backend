<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\Destination;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderTicketControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        return $user;
    }

    /** @test */
    public function user_can_cancel_order()
    {
        $this->authenticateUser();

        $order = Order::factory()->create(['status' => 'pending']);
        $ticket = Ticket::find($order->ticket_id);

        $response = $this->putJson("/api/tickets/cancel/{$order->order_id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Order cancelled successfully']);

        $this->assertDatabaseMissing('orders', ['order_id' => $order->order_id, 'status' => 'pending']);
        $this->assertDatabaseMissing('tickets', ['ticket_id' => $ticket->ticket_id]);
    }

    /** @test */
    public function only_pending_order_can_be_cancelled()
    {
        $this->authenticateUser();

        $order = Order::factory()->create(['status' => 'success']);

        $response = $this->putJson("/api/tickets/cancel/{$order->order_id}");

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Only pending orders can be cancelled']);
    }

    /** @test */
    public function user_can_complete_order()
    {
        $this->authenticateUser();

        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->putJson("/api/tickets/complete/{$order->order_id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Order completed successfully']);

        $this->assertDatabaseHas('orders', ['order_id' => $order->order_id, 'status' => 'success']);
    }

    /** @test */
    public function only_pending_order_can_be_completed()
    {
        $this->authenticateUser();

        $order = Order::factory()->create(['status' => 'success']);

        $response = $this->putJson("/api/tickets/complete/{$order->order_id}");

        $response->assertStatus(400)
                 ->assertJson(['message' => 'Only pending orders can be completed']);
    }
}
