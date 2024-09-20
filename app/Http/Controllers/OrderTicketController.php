<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Bus;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;

class OrderTicketController extends Controller
{
    public function orderTicket(OrderRequest $request)
    {
        $bus = Bus::find($request->bus_id);

        if ($bus->seat_capacity <= 0) {
            return response()->json(['message' => 'Bus is full'], 400);
        }

        $ticket = Ticket::create([
            'user_id' => $request->user_id,
            'destination_id' => $request->destination_id,
            'seat_number' => $request->seat_number,
            'price' => $bus->price,
        ]);

        Order::create([
            'user_id' => $request->user_id,
            'ticket_id' => $ticket->ticket_id,
            'total_price' => $ticket->price,
            'status' => 'pending',
        ]);

        $bus->seat_capacity -= 1;
    }

    public function cancelOrder($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Only pending orders can be cancelled'], 400);
        }

        $order->status = 'cancel';
        $order->save();

        $ticket = Ticket::find($order->ticket_id);
        $ticket->delete();

        return response()->json(['message' => 'Order cancelled successfully'], 200);
    }

    public function completeOrder($id)
    {
        $order = Order::find($id);
        $bus = Bus::find($order->ticket->destination->bus_id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Only pending orders can be completed'], 400);
        }

        $order->status = 'success';
        $order->save();

        $bus->seat_capacity += 1;
        $bus->save();

        return response()->json(['message' => 'Order completed successfully'], 200);
    }
}
