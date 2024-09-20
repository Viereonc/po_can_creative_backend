<?php

namespace App\Http\Controllers;

use App\Http\Requests\DestinationRequest;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::all();

        return response()->json($destinations);
    }

    public function show($id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }

        return response()->json($destination);
    }

    public function store(DestinationRequest $request)
    {
        $destination = Destination::create($request->validated());

        return response()->json($destination, 201);
    }

    public function update(DestinationRequest $request, $id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }

        $destination->update($request->validated());

        return response()->json($destination, 200);
    }

    public function destroy($id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }

        $destination->delete();

        return response()->json(['message' => 'Destination deleted successfully'], 200);
    }

    public function filterByLocation(Request $request)
    {
        $locationStart = $request->input('start_location');
        $locationEnd = $request->input('end_location');

        $destinations = Destination::byLocation($locationStart, $locationEnd)->get();

        if ($destinations->isEmpty()) {
            return response()->json(['message' => 'No destinations found'], 404);
        }

        return response()->json($destinations, 200);
    }

    public function checkIfDeparted($id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }

        $hasDeparted = $destination->hasDeparted();

        return response()->json([
            'destination' => $destination->getDestination(),
            'has_departed' => $hasDeparted,
            'departure_time' => $destination->getDepartureTime(),
        ], 200);
    }

    public function checkIfNotDeparted($id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }

        $hasNotDeparted = $destination->hasNotDeparted();

        return response()->json([
            'destination' => $destination->getDestination(),
            'has_not_departed' => $hasNotDeparted,
            'departure_time' => $destination->getDepartureTime(),
        ], 200);
    }
}
