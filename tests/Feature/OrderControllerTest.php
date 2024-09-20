<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        return $user;
    }

    /** @test */
    public function get_all_orders()
    {
        $this->authenticateUser();

        Order::factory()->count(3)->create();

        $response = $this->getJson('/api/orders');
        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function show_specific_order()
    {
        $this->authenticateUser();

        $order = Order::factory()->create();

        $response = $this->getJson("/api/orders/{$order->order_id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'order_id' => $order->order_id,
                     'user_id' => $order->user_id,
                     'ticket_id' => $order->ticket_id,
                     'total_price' => $order->total_price,
                     'status' => $order->status,
                 ]);
    }

    /** @test */
    public function post_order_data()
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $ticket = Ticket::factory()->create();

        $data = [
            'user_id' => $user->id,
            'ticket_id' => $ticket->ticket_id,
            'total_price' => 5000,
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/orders/post', $data);

        $response->assertStatus(201)
                 ->assertJson($data);
    }

    /** @test */
    public function update_order_data()
    {
        $this->authenticateUser();

        $order = Order::factory()->create();

        $data = [
            'user_id' => $order->user_id,
            'ticket_id' => $order->ticket_id,
            'total_price' => 6000,
            'status' => 'success',
        ];

        $response = $this->putJson("/api/orders/put/{$order->order_id}", $data);

        $response->assertStatus(200)
                 ->assertJson($data);
    }

    /** @test */
    public function delete_order_data()
    {
        $this->authenticateUser();
        $order = Order::factory()->create();
        $response = $this->deleteJson("/api/orders/delete/{$order->order_id}");
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Order deleted successfully']);
    }

    /** @test */
    public function get_pending_orders()
    {
        $this->authenticateUser();

        Order::factory()->create(['status' => 'pending']);

        $response = $this->getJson('/api/orders/check-order-status/pending');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    /** @test */
    public function get_success_orders()
    {
        $this->authenticateUser();

        Order::factory()->create(['status' => 'success']);

        $response = $this->getJson('/api/orders/check-order-status/success');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    /** @test */
    public function get_cancelled_orders()
    {
        $this->authenticateUser();

        Order::factory()->create(['status' => 'cancel']);

        $response = $this->getJson('/api/orders/check-order-status/cancelled');

        $response->assertStatus(200)
                 ->assertJsonCount(1);
    }

    /** @test */
    public function check_order_status()
    {
        $this->authenticateUser();

        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->getJson("/api/orders/check-order-status-user/{$order->order_id}/status");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Order is pending']);
    }
}
