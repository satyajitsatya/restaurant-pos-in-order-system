<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class KitchenController extends Controller
{
    /**
     * Display kitchen dashboard with active orders
     */
    public function index()
    {
        // Get orders that are accepted or preparing
        $activeOrders = Order::with(['table', 'items.product'])
            ->whereIn('status', ['accepted', 'preparing'])
            ->orderBy('created_at')
            ->get();

        // Group orders by table for better organization
        $groupedOrders = $activeOrders->groupBy('table.table_number');

        return view('kitchen.index', compact('groupedOrders', 'activeOrders'));
    }

    /**
     * Update order item status from kitchen
     */
    public function updateItemStatus(OrderItem $orderItem, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:preparing,ready'
        ]);

        $orderItem->update(['status' => $validated['status']]);

        $order = $orderItem->order;

        // If all items are ready, update order status to ready
        $allItemsReady = $order->items()->where('status', '!=', 'ready')->count() === 0;

        if ($allItemsReady && $order->status !== 'ready') {
            $order->update(['status' => 'ready']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item status updated successfully',
            'order_status_updated' => $allItemsReady,
            'new_order_status' => $allItemsReady ? 'ready' : $order->status
        ]);
    }

    /**
     * Get live kitchen data for real-time updates
     */
    public function getLiveData()
    {

        $activeOrders = Order::whereIn('status', ['accepted', 'preparing'])
            ->orderBy('created_at')
            ->count();
        // $activeOrders = Order::with(['table', 'items.product'])
        //     ->whereIn('status', ['accepted', 'preparing'])
        //     ->orderBy('created_at')
        //     ->get();

        // $groupedOrders = $activeOrders->groupBy('table.table_number');

        return response()->json([
            'orders' => $activeOrders,
            'timestamp' => now()->timestamp
        ]);
    }

    /**
     * Mark entire order as ready
     */
    public function markOrderReady(Order $order)
    {
        // Update all order items to ready
        $order->items()->update(['status' => 'ready']);

        // Update order status
        $order->update(['status' => 'ready']);

        return response()->json([
            'success' => true,
            'message' => 'Order marked as ready!'
        ]);
    }
}
