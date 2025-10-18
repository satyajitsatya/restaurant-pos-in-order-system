<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display the restaurant menu
     * This is what customers see when they scan QR code
     */
    public function index(Request $request)
    {
        // Get all active categories, ordered by sort_order
        $categories = Category::active()
            ->orderBy('sort_order')
            ->withCount('products') // Get product count for each category
            ->withCount('activeProducts') // Get active product count for each category
            ->get();

        // Build query for products
        $productsQuery = Product::with(['category' => function ($query) {
            $query->active();
        }]) // Eager load category relationship

            ->active()
            ->whereHas('category', function ($query) {
                $query->active();
            }); // Only active products

        // Apply filters if provided
        if ($request->filled('category')) {
            $productsQuery->where('category_id', $request->category);
        }

        if ($request->filled('veg_only')) {
            $productsQuery->where('is_veg', true);
        }

        if ($request->filled('search')) {
            $productsQuery->where('name', 'like', '%' . $request->search . '%');
        }

        // Get products with pagination
        $products = $productsQuery->paginate(12);

        // Get table information if provided
        $tableInfo = null;
        if ($request->filled('table')) {
            $tableInfo = Table::find($request->table);
        }

        $table = Table::all();
        // dd($table);
        // exit();

        return view('menu.index', compact('categories', 'products', 'table', 'tableInfo'));
    }

    /**
     * Show product details
     */
    public function show(Product $product)
    {
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'formatted_price' => $product->formatted_price,
            'image' => $product->image,
            'is_veg' => $product->is_veg,
            'spice_level' => $product->spice_level,
            'prep_time' => $product->prep_time
        ]);
    }

    /**
     * Place a new order
     */
    public function placeOrder(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'required|string|max:100',
            'guest_count' => 'required|integer|min:1',
            'payment_method' => 'required|in:counter,card,cash',
            'notes' => 'nullable|string',
            'cart_items' => 'required|array|min:1',
            'cart_items.*.product_id' => 'required|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // Start database transaction
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            foreach ($validated['cart_items'] as $item) {
                $product = Product::find($item['product_id']);
                $totalAmount += $product->price * $item['quantity'];
            }

            // Create the order
            $order = Order::create([
                'table_id' => $validated['table_id'],
                'customer_name' => $validated['customer_name'],
                'guest_count' => $validated['guest_count'],
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending'
            ]);

            // Create order items
            foreach ($validated['cart_items'] as $item) {
                $product = Product::find($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product->price // Store current price
                ]);
            }

            // Commit transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'total_amount' => $totalAmount
            ]);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.'
            ], 500);
        }
    }

    /**
     * Get order status for customer
     */
    public function orderStatus($orderId)
    {
        $order = Order::with(['table', 'items.product'])
            ->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json([
            'order_id' => $order->id,
            'status' => $order->status,
            'table_number' => $order->table->table_number,
            'customer_name' => $order->customer_name,
            'total_amount' => $order->formatted_total,
            'estimated_time' => $this->calculateEstimatedTime($order)
        ]);
    }

    /**
     * Calculate estimated preparation time
     */
    private function calculateEstimatedTime($order)
    {
        $maxPrepTime = $order->items->max(function ($item) {
            return $item->product->prep_time;
        });

        return $maxPrepTime + 5; // Add 5 minutes buffer
    }

    // Add this method to the existing MenuController class

    /**
     * Show order tracking page
     */
    public function trackOrder(Request $request, $orderId = null)
    {
        // If order ID is passed in URL
        if ($orderId) {
            $order = Order::with(['table', 'items.product'])->find($orderId);

            if (!$order) {
                return redirect()->route('menu.index')->with('error', 'Order not found!');
            }

            // Calculate progress percentage
            $progressPercentage = $this->getOrderProgress($order->status);

            // Calculate estimated time
            $estimatedTime = $this->calculateEstimatedTime($order);

            return view('menu.track-order', compact('order', 'progressPercentage', 'estimatedTime'));
        }

        // If accessing track page without order ID
        return view('menu.track-order');
    }

    /**
     * Get order status via AJAX for real-time updates
     */
    public function getOrderStatus($orderId)
    {
        $order = Order::with(['table', 'items.product'])->find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json([
            'order_id' => $order->id,
            'status' => $order->status,
            'status_text' => ucfirst($order->status),
            'table_number' => $order->table->table_number,
            'customer_name' => $order->customer_name,
            'total_amount' => number_format($order->total_amount, 0),
            'created_at' => $order->created_at->format('M d, Y H:i'),
            'estimated_time' => $this->calculateEstimatedTime($order),
            'items' => $order->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'status' => $item->status,
                    'status_text' => ucfirst($item->status)
                ];
            }),
            'progress_percentage' => $this->getOrderProgress($order->status)
        ]);
    }

    /**
     * Calculate order progress percentage
     */
    private function getOrderProgress($status)
    {
        switch ($status) {
            case 'pending':
                return 10;
            case 'accepted':
                return 25;
            case 'preparing':
                return 50;
            case 'ready':
                return 80;
            case 'served':
                return 100;
            case 'cancelled':
                return 0;
            default:
                return 0;
        }
    }
}
