<?php

namespace App\Http\Controllers;

use App\Http\Requests\DriverRequest;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();

        return response()->json($drivers);
    }

    public function show($id)
    {
        $driver = Driver::with('user', 'bus')->find($id);

        if (!$driver) {
            return response()->json(['message' => 'Driver not found'], 404);
        }

        $driver->license_number = $driver->license_number;

        return response()->json($driver);
    }

    public function store(DriverRequest $request)
    {
        $driver = Driver::create($request->validated());

        $driver->license_number = $driver->license_number;

        return response()->json($driver, 201);
    }

    public function update(DriverRequest $request, $id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json(['message' => 'Driver not found'], 404);
        }

        $driver->update($request->validated());

        $driver->license_number = $driver->license_number;

        return response()->json($driver, 200);
    }

    public function destroy($id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json(['message' => 'Driver not found'], 404);
        }

        $driver->delete();

        return response()->json(['message' => 'Driver deleted successfully'], 200);
    }

    public function getByBus($busId)
    {
        $drivers = Driver::byBus($busId)->get();

        if ($drivers->isEmpty()) {
            return response()->json(['message' => 'No drivers found for the selected bus'], 404);
        }

        return response()->json($drivers, 200);
    }

    public function getByUser($userId)
    {
        $drivers = Driver::byUser($userId)->get();

        if ($drivers->isEmpty()) {
            return response()->json(['message' => 'No drivers found for the selected user'], 404);
        }

        return response()->json($drivers, 200);
    }
}
