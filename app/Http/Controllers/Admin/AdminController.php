<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display admin dashboard with key metrics
     * This shows overview of restaurant performance
     */
    public function dashboard()
    {
        // Get today's date
        $today = Carbon::today();

        // Calculate key metrics
        $todaySales = Order::whereDate('created_at', $today)
            ->whereIn('status', ['served', 'ready'])
            ->sum('total_amount');

        $pendingOrders = Order::where('status', 'pending')->count();

        $totalOrders = Order::whereDate('created_at', $today)->count();

        $activeTables = Table::whereHas('orders', function ($query) {
            $query->whereIn('status', ['pending', 'accepted', 'preparing', 'ready']);
        })->count();

        // Get recent orders with relationships
        $recentOrders = Order::with(['table', 'items.product'])
            ->latest()
            ->take(10)
            ->get();

        // Get top selling products today
        $topProducts = Product::join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereDate('orders.created_at', $today)
            ->groupBy('products.id', 'products.name')
            ->selectRaw('products.id, products.name, SUM(order_items.quantity) as total_sold')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todaySales',
            'pendingOrders',
            'totalOrders',
            'activeTables',
            'recentOrders',
            'topProducts'
        ));
    }

    /**
     * Get real-time data for AJAX updates
     */
    public function getLiveData()
    {
        $pendingOrders = Order::with(['table', 'items.product'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $newOrdersCount = Order::where('status', 'pending')
            ->where('created_at', '>', now()->subMinutes(1))
            ->count();

        return response()->json([
            'pending_orders' => $pendingOrders,
            'new_orders_count' => $newOrdersCount,
            'timestamp' => now()->timestamp
        ]);
    }
}
