<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusRequest;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::all();

        return response()->json($buses);
    }

    public function show($id)
    {
        $bus = Bus::with('drivers')->find($id);

        if (!$bus) {
            return response()->json(['message' => 'Bus not found'], 404);
        }

        return response()->json($bus);
    }

    public function store(BusRequest $request)
    {
        $bus = Bus::create($request->validated());

        return response()->json($bus, 201);
    }

    public function update(BusRequest $request, $id)
    {
        $bus = Bus::find($id);

        if (!$bus) {
            return response()->json(['message' => 'Bus not found'], 404);
        }

        $bus->update($request->validated());

        return response()->json($bus, 200);
    }

    public function destroy($id)
    {
        $bus = Bus::find($id);

        if (!$bus) {
            return response()->json(['message' => 'Bus not found'], 404);
        }

        $bus->delete();

        return response()->json(['message' => 'Bus deleted successfully'], 200);
    }

    public function checkAvailableSeats($id, $bookedSeats)
    {
        $bus = Bus::find($id);

        if (!$bus) {
            return response()->json(['message' => 'Bus not found'], 404);
        }

        $availableSeats = $bus->hasAvailableSeats($bookedSeats);

        if ($availableSeats) {
            return response()->json(['message' => 'Seats are available'], 200);
        } else {
            return response()->json(['message' => 'No available seats'], 400);
        }
    }

    public function getBusDrivers($id)
    {
        $bus = Bus::with('drivers')->find($id);

        if (!$bus) {
            return response()->json(['message' => 'Bus not found'], 404);
        }

        return response()->json($bus->drivers, 200);
    }

    public function getBusesWithCapacityAbove($capacity)
    {
        $buses = Bus::capacityAbove($capacity)->get();

        if ($buses->isEmpty()) {
            return response()->json(['message' => 'No buses found with capacity above ' . $capacity], 404);
        }

        return response()->json($buses, 200);
    }

    public function getBusesAvailableOnDestination($destinationId)
    {
        $buses = Bus::availableOnDestination($destinationId)->get();

        if ($buses->isEmpty()) {
            return response()->json(['message' => 'No buses available for this destination'], 404);
        }

        return response()->json($buses, 200);
    }
}
