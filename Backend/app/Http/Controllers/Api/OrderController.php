<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    /**
     * List orders for the authenticated user
     */
    public function index(Request $request)
    {
        $orders = Order::with(['user', 'items.product'])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return response()->json($orders);
    }

    /**
     * Show a single order for the authenticated user
     */
    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $order->load(['user', 'items.product']);

        return response()->json($order);
    }

    /**
     * Cancel an order (only if status is pending)
     */
    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Only pending orders can be cancelled.'], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Order cancelled successfully.', 'order' => $order->fresh(['user', 'items.product'])]);
    }
}
