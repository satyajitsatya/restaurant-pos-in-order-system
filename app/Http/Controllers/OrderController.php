<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Display all orders with filtering
     */
    public function index(Request $request)
    {
        $query = Order::with(['table', 'items.product']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('table')) {
            $query->where('table_id', $request->table);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Search by customer name or order ID
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                    ->orWhere('id', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show single order details
     */
    public function show(Order $order)
    {
        $order->load(['table', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status (AJAX)
     */
    public function updateStatus(Order $order, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,preparing,ready,served,cancelled'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $validated['status']]);

        // If order is accepted, update all items to preparing
        if ($validated['status'] === 'accepted') {
            $order->items()->update(['status' => 'preparing']);
        }

        // If order is ready, update all items to ready
        if ($validated['status'] === 'ready') {
            $order->items()->update(['status' => 'ready']);
        }

        // If order is served, update all items to ready
        if ($validated['status'] === 'served') {
            $order->items()->update(['status' => 'served']);
        }

        return response()->json([
            'success' => true,
            'message' => "Order status updated from {$oldStatus} to {$validated['status']}",
            'new_status' => $validated['status']
        ]);
    }

    /**
     * Update individual item status (for kitchen)
     */
    public function updateItemStatus(OrderItem $orderItem, Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,served'
        ]);

        $orderItem->update(['status' => $validated['status']]);

        // Check if all items are ready, update order status
        $order = $orderItem->order;
        $allItemsReady = $order->items()->where('status', '!=', 'ready')->count() === 0;

        if ($allItemsReady && $order->status !== 'ready') {
            $order->update(['status' => 'ready']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item status updated successfully',
            'order_status_updated' => $allItemsReady
        ]);
    }

    /**
     * Get pending orders for real-time updates
     */
    public function getPendingOrders()
    {
        $orders = Order::with(['table', 'items.product'])
            ->whereIn('status', ['pending', 'accepted', 'preparing'])
            ->latest()
            ->get();

        return response()->json([
            'orders' => $orders,
            'count' => $orders->count()
        ]);
    }
}
