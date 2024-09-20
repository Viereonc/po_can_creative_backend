<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderTicketController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
});

Route::middleware('auth:sanctum')->prefix('buses')->group(function () {
    Route::get('/', [BusController::class, 'index']);
    Route::get('/{id}', [BusController::class, 'show']);
    Route::post('/post', [BusController::class, 'store']);
    Route::put('/put/{id}', [BusController::class, 'update']);
    Route::delete('/delete/{id}', [BusController::class, 'destroy']);
    Route::get('/{id}/available-seats/{bookedSeats}', [BusController::class, 'checkAvailableSeats']);
    Route::get('/{id}/drivers', [BusController::class, 'getBusDrivers']);
    Route::get('/capacity-above/{capacity}', [BusController::class, 'getBusesWithCapacityAbove']);
    Route::get('/destination/{destinationId}', [BusController::class, 'getBusesAvailableOnDestination']);
});

Route::middleware('auth:sanctum')->prefix('destinations')->group(function () {
    Route::get('/', [DestinationController::class, 'index']);
    Route::get('/{id}', [DestinationController::class, 'show']);
    Route::post('/post', [DestinationController::class, 'store']);
    Route::put('/put/{id}', [DestinationController::class, 'update']);
    Route::delete('/delete/{id}', [DestinationController::class, 'destroy']);
    Route::get('/filter', [DestinationController::class, 'filterByLocation']);
    Route::get('/{id}/check-departed', [DestinationController::class, 'checkIfDeparted']);
    Route::get('/{id}/check-not-departed', [DestinationController::class, 'checkIfNotDeparted']);
});

Route::middleware('auth:sanctum')->prefix('drivers')->group(function () {
    Route::get('/', [DriverController::class, 'index']);
    Route::get('/{id}', [DriverController::class, 'show']);
    Route::post('/post', [DriverController::class, 'store']);
    Route::put('/put/{id}', [DriverController::class, 'update']);
    Route::delete('/delete/{id}', [DriverController::class, 'destroy']);
    Route::get('/by-bus/{busId}', [DriverController::class, 'getByBus']);
    Route::get('/by-user/{userId}', [DriverController::class, 'getByUser']);
});

Route::middleware('auth:sanctum')->prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::post('/post', [OrderController::class, 'store']);
    Route::put('/put/{id}', [OrderController::class, 'update']);
    Route::delete('/delete/{id}', [OrderController::class, 'destroy']);
    Route::get('/check-order-status/pending', [OrderController::class, 'getPendingOrders']);
    Route::get('/check-order-status/success', [OrderController::class, 'getSuccessOrders']);
    Route::get('/check-order-status/cancelled', [OrderController::class, 'getCancelledOrders']);
    Route::get('/check-order-status-user/{id}/status', [OrderController::class, 'checkOrderStatus']);
});

Route::middleware('auth:sanctum')->prefix('tickets')->group(function () {
    Route::get('/', [TicketController::class, 'index']);
    Route::get('/{id}', [TicketController::class, 'show']);
    Route::post('/post', [TicketController::class, 'store']);
    Route::put('/put/{id}', [TicketController::class, 'update']);
    Route::delete('/delete/{id}', [TicketController::class, 'destroy']);
    Route::get('/destination/{destinationId}', [TicketController::class, 'getTicketsForDestination']);
    Route::get('/price-above/{amount}', [TicketController::class, 'getTicketsWithPriceAbove']);
});

Route::middleware('auth:sanctum')->prefix('tickets')->group(function () {
    Route::post('/order', [OrderTicketController::class, 'orderTicket']);
    Route::put('/cancel/{id}', [OrderTicketController::class, 'cancelOrder']);
    Route::put('/complete/{id}', [OrderTicketController::class, 'completeOrder']);
});
