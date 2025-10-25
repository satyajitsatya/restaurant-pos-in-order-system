<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display admin dashboard with key metrics and analytics
     */
    public function dashboard(Request $request)
    {
        // Get selected date (default to today)
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));
        $date = Carbon::parse($selectedDate);

        // Get selected month for analytics (default to current month)
        $selectedMonth = $request->get('month', Carbon::now()->format('Y-m'));
        $month = Carbon::parse($selectedMonth . '-01');

        // Calculate key metrics for selected date
        $todaySales = Order::whereDate('created_at', $date)
            ->whereIn('status', ['served', 'ready'])
            ->sum('total_amount');

        $pendingOrders = Order::where('status', 'pending')->count();

        $totalOrders = Order::whereDate('created_at', $date)->count();

        $activeTables = Table::whereHas('orders', function ($query) {
            $query->whereIn('status', ['pending', 'accepted', 'preparing', 'ready']);
        })->count();

        // Get recent orders for selected date
        $recentOrders = Order::with(['table', 'items.product'])
            ->whereDate('created_at', $date)
            ->latest()
            ->take(10)
            ->get();

        // Get top selling products for selected date
        $topProducts = Product::join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereDate('orders.created_at', $date)
            ->groupBy('products.id', 'products.name')
            ->selectRaw('products.id, products.name, SUM(order_items.quantity) as total_sold')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Monthly Analytics Data
        $monthlyAnalytics = $this->getMonthlyAnalytics($month);

        // Daily sales for the selected month (for chart)
        $dailySalesData = $this->getDailySalesData($month);

        // Compare with previous period
        $previousDate = $date->copy()->subDay();
        $previousDaySales = Order::whereDate('created_at', $previousDate)
            ->whereIn('status', ['served', 'ready'])
            ->sum('total_amount');

        $salesGrowth = $previousDaySales > 0 ?
            (($todaySales - $previousDaySales) / $previousDaySales) * 100 : ($todaySales > 0 ? 100 : 0);

        return view('admin.dashboard', compact(
            'todaySales',
            'pendingOrders',
            'totalOrders',
            'activeTables',
            'recentOrders',
            'topProducts',
            'selectedDate',
            'selectedMonth',
            'monthlyAnalytics',
            'dailySalesData',
            'salesGrowth'
        ));
    }

    /**
     * Get monthly analytics data
     */
    private function getMonthlyAnalytics($month)
    {
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $totalSales = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->whereIn('status', ['served', 'ready'])
            ->sum('total_amount');

        $totalOrders = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Top categories for the month
        $topCategories = Category::join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('categories.id', 'categories.name')
            ->selectRaw('categories.name, SUM(order_items.quantity * order_items.price) as total_sales')
            ->orderByDesc('total_sales')
            ->take(5)
            ->get();

        return [
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'top_categories' => $topCategories,
            'month_name' => $month->format('F Y')
        ];
    }

    /**
     * Get daily sales data for chart
     */
    private function getDailySalesData($month)
    {
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();

        $dailySales = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total_sales')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->whereIn('status', ['served', 'ready'])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill missing dates with 0
        $data = [];
        $currentDate = $startOfMonth->copy();

        while ($currentDate <= $endOfMonth) {
            $dateString = $currentDate->format('Y-m-d');
            $data[] = [
                'date' => $currentDate->format('M d'),
                'sales' => $dailySales->get($dateString)->total_sales ?? 0
            ];
            $currentDate->addDay();
        }

        return $data;
    }

    /**
     * Get real-time data for AJAX updates
     */
    public function getLiveData()
    {
        // Get selected date (default to today)
        $selectedDate =  Carbon::today()->format('Y-m-d');
        $date = Carbon::parse($selectedDate);


        $todaySales = Order::whereDate('created_at', $date)
            ->whereIn('status', ['served', 'ready'])
            ->sum('total_amount');

        // Compare with previous period
        $previousDate = $date->copy()->subDay();
        $previousDaySales = Order::whereDate('created_at', $previousDate)
            ->whereIn('status', ['served', 'ready'])
            ->sum('total_amount');

        $salesGrowth = $previousDaySales > 0 ?
            (($todaySales - $previousDaySales) / $previousDaySales) * 100 : ($todaySales > 0 ? 100 : 0);

        $totalOrders = Order::whereDate('created_at', $date)->count();


        $pendingOrders_count = Order::with(['table', 'items.product'])
            ->where('status', 'pending')
            ->count();
        // ->latest()
        // ->get();

        $newOrdersCount = Order::with(['table', 'items.product'])
            ->where('status', 'pending')
            ->where('created_at', '>', now()->subMinutes(1))
            ->latest()
            ->get();

        $activeTables = Table::whereHas('orders', function ($query) {
            $query->whereIn('status', ['pending', 'accepted', 'preparing', 'ready']);
        })->count();

        return response()->json([
            'pendingOrders_count' => $pendingOrders_count,
            'activeTables' => $activeTables,
            'todaySales' => $todaySales,
            'salesGrowth' => $salesGrowth,
            'totalOrders' => $totalOrders,
            'new_orders' => $newOrdersCount,
            'timestamp' => now()->timestamp
        ]);
    }



    /**
     * Refresh recent orders for dashboard
     */
    public function refreshRecentOrders(Request $request)
    {
        // You can get the date from the request if needed, otherwise default to today
        $date = $request->input('date', Carbon::today()->toDateString());

        $recentOrders = Order::with(['table', 'items.product'])
            ->whereDate('created_at', $date)
            ->latest()
            ->take(10)
            ->get();

        // Render the partial view with the new order data
        $view = view('admin.orders.recent_orders_table', [
            'recentOrders' => $recentOrders,
            'selectedDate' => $date
        ])->render();

        return response()->json(['html' => $view]);
    }
}
