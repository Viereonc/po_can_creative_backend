<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();

        return response()->json($orders);
    }

    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        return response()->json($order);
    }

    public function store(OrderRequest $request)
    {
        $order = Order::create($request->validated());

        return response()->json($order, 201);
    }

    public function update(OrderRequest $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->update($request->validated());

        return response()->json($order, 200);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully'], 200);
    }

    public function getPendingOrders()
    {
        $orders = Order::pending()->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No pending orders found'], 404);
        }

        return response()->json($orders);
    }

    public function getSuccessOrders()
    {
        $orders = Order::success()->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No successful orders found'], 404);
        }

        return response()->json($orders);
    }

    public function getCancelledOrders()
    {
        $orders = Order::cancelled()->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No cancelled orders found'], 404);
        }

        return response()->json($orders);
    }

    public function checkOrderStatus($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->isPending()) {
            return response()->json(['message' => 'Order is pending'], 200);
        }

        if ($order->isSuccess()) {
            return response()->json(['message' => 'Order is successful'], 200);
        }

        if ($order->isCancelled()) {
            return response()->json(['message' => 'Order is cancelled'], 200);
        }

        return response()->json(['message' => 'Order status is unknown'], 400);
    }
}
