<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::all();

        return response()->json($tickets);
    }

    public function show($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        return response()->json($ticket);
    }

    public function store(TicketRequest $request)
    {
        $ticket = Ticket::create($request->validated());

        return response()->json($ticket, 201);
    }

    public function update(TicketRequest $request, $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        $ticket->update($request->validated());

        return response()->json($ticket, 200);
    }

    public function destroy($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        $ticket->delete();

        return response()->json(['message' => 'Ticket deleted successfully'], 200);
    }

    public function getTicketsForDestination($destinationId)
    {
        $tickets = Ticket::forDestination($destinationId)->get();

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No tickets found for this destination'], 404);
        }

        return response()->json($tickets, 200);
    }

    public function getTicketsWithPriceAbove($amount)
    {
        $tickets = Ticket::priceAbove($amount)->get();

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No tickets found with price above ' . $amount], 404);
        }

        return response()->json($tickets, 200);
    }
}
