<?php

namespace App\Http\Controllers;

use App\Http\Resources\DefaultResource;
use App\Http\Services\OrderService;
use Illuminate\Http\Request;

class OrderControlle extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json([
                'status' => false,
                'status_code' => 400,
                'message' => 'User ID is required',
            ], 400);
        }

        $orderService = new OrderService();
        $orders = $orderService->getOrders($userId);

        return new DefaultResource($orders);
    }

    public function show($orderId)
    {
        $orderService = new OrderService();
        $order = $orderService->getOrder($orderId);

        if (!$order) {
            return response()->json([
                'status' => false,
                'status_code' => 404,
                'message' => 'Order not found',
            ], 404);
        }

        return new DefaultResource($order);
    }
}
