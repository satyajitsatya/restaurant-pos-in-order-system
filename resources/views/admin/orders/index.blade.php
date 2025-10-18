{{-- @extends('layouts.admin')

@section('title', 'Orders Management')
@section('page-title', 'Orders')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Orders Management</h1>
        <p class="text-gray-600">View and manage customer orders</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by order ID or customer name..." class="form-input">
            </div>
            <div>
                <select name="status" class="form-input">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Preparing</option>
                    <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                    <option value="served" {{ request('status') === 'served' ? 'selected' : '' }}>Served</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <input type="date" name="date" value="{{ request('date') }}" class="form-input">
            </div>
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('admin.orders.index') }}" class="btn-secondary">Clear</a>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Details</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="orders-table-body">
                @forelse($orders as $order)
                <tr class="order-row" data-order-id="{{ $order->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <a href="{{ route('admin.orders.show', $order) }}" class="hover:underline">
                                <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                            </a>
                            <div class="text-sm text-gray-500">{{ $order->customer_name }}</div>
                            <div class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y H:i') }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $order->table->table_number }}</div>
                        <div class="text-xs text-gray-500">{{ $order->guest_count }} guests</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $order->items->count() }} items</div>
                        <div class="text-xs text-gray-500">
                            @foreach($order->items->take(2) as $item)
                                {{ $item->product->name }}@if(!$loop->last), @endif
                            @endforeach
                            @if($order->items->count() > 2)
                                <span class="text-gray-400">+{{ $order->items->count() - 2 }} more</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ₹{{ number_format($order->total_amount, 0) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="order-status px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'accepted') bg-blue-100 text-blue-800
                            @elseif($order->status === 'preparing') bg-orange-100 text-orange-800
                            @elseif($order->status === 'ready') bg-green-100 text-green-800
                            @elseif($order->status === 'served') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="capitalize">{{ $order->payment_method }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            
                            @if($order->status === 'pending')
                                <button class="text-green-600 hover:text-green-900 update-status-btn" 
                                        data-order-id="{{ $order->id }}" data-status="accepted">Accept</button>
                                <button class="text-red-600 hover:text-red-900 update-status-btn" 
                                        data-order-id="{{ $order->id }}" data-status="cancelled">Cancel</button>
                            @elseif($order->status === 'accepted')
                                <button class="text-orange-600 hover:text-orange-900 update-status-btn" 
                                        data-order-id="{{ $order->id }}" data-status="preparing">Mark Preparing</button>
                            @elseif($order->status === 'preparing')
                                <button class="text-green-600 hover:text-green-900 update-status-btn" 
                                        data-order-id="{{ $order->id }}" data-status="ready">Mark Ready</button>
                            @elseif($order->status === 'ready')
                                <button class="text-blue-600 hover:text-blue-900 update-status-btn" 
                                        data-order-id="{{ $order->id }}" data-status="served">Mark Served</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No orders found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh every 10 seconds
    setInterval(function() {
        // Only refresh if we're on the first page and no filters
        if (!window.location.search) {
            location.reload();
        }
    }, 10000);
    
    // Update order status
    $('.update-status-btn').click(function() {
        const button = $(this);
        const orderId = button.data('order-id');
        const status = button.data('status');
        
        button.prop('disabled', true).text('Updating...');
        
        $.post(`/admin/orders/${orderId}/status`, {
            status: status
        })
        .done(function(response) {
            if (response.success) {
                showToast(response.message);
                // Reload page to show updated status
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        })
        .fail(function() {
            showToast('Failed to update order status', 'error');
        })
        .always(function() {
            button.prop('disabled', false);
        });
    });
});
</script>
@endpush --}}



@extends('layouts.admin')

@section('title', 'Orders Management')
@section('page-title', 'Orders')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Enhanced Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <button id="enableAlertsBtn" onclick="location.reload()"
                     class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
                     Reload
                     </button>
                    <h1 class="text-3xl font-bold text-gray-900">Orders Management</h1>
                    <p class="mt-2 text-sm text-gray-600">View and manage customer orders in real-time</p>
                </div>
                <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                    <!-- Live indicator -->
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse mr-2"></div>
                        <span class="text-sm text-gray-600">Live updates</span>
                    </div>
                    <!-- pending orders badge -->
                    <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium" id="pending-orders-count">
                        {{ $pending_orders }} Pending Orders
                    </div>
                    <!-- Total orders badge -->
                    <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $orders->total() }} Total Orders
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Filters Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                    </svg>
                </div>
                
                <form method="GET" class="space-y-4 lg:space-y-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Search by order ID or customer name..." 
                                       class="form-input pl-10 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                            </div>
                        </div>
                        
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="form-input border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>🟡 Pending</option>
                                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>🔵 Accepted</option>
                                <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>🟠 Preparing</option>
                                <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>🟢 Ready</option>
                                <option value="served" {{ request('status') === 'served' ? 'selected' : '' }}>⚫ Served</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>🔴 Cancelled</option>
                            </select>
                        </div>
                        
                        <!-- Date Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}" 
                                   class="form-input border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                        </div>
                    </div>
                    
                    <!-- Filter Actions -->
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 pt-4 border-t">
                        <a href="{{ route('admin.orders.index') }}" 
                           class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                            Clear Filters
                        </a>
                        <button type="submit" 
                                class="inline-flex justify-center items-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Content -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Desktop Table View -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order Details</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Table & Guests</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Items</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="orders-table-body">
                        @forelse($orders as $order)
                        <tr class="order-row hover:bg-gray-50 transition-colors duration-200" data-order-id="{{ $order->id }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <span class="text-orange-600 font-bold text-sm">#{{ substr($order->id, -3) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-sm font-semibold text-gray-900 hover:text-orange-600 transition-colors">
                                            Order #{{ $order->id }}
                                        </a>
                                        <div class="text-sm text-gray-600 font-medium">{{ $order->customer_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Table {{ $order->table->table_number }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->guest_count }} guests</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $order->items->count() }} items</div>
                                <div class="text-xs text-gray-500 max-w-48 truncate">
                                    @foreach($order->items->take(2) as $item)
                                        {{ $item->product->name }}@if(!$loop->last), @endif
                                    @endforeach
                                    @if($order->items->count() > 2)
                                        <span class="text-gray-400">+{{ $order->items->count() - 2 }} more</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-lg font-bold text-gray-900">₹{{ number_format($order->total_amount, 0) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="order-status px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                    @elseif($order->status === 'accepted') bg-blue-100 text-blue-800 border border-blue-200
                                    @elseif($order->status === 'preparing') bg-orange-100 text-orange-800 border border-orange-200
                                    @elseif($order->status === 'ready') bg-green-100 text-green-800 border border-green-200
                                    @elseif($order->status === 'served') bg-gray-100 text-gray-800 border border-gray-200
                                    @else bg-red-100 text-red-800 border border-red-200
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="capitalize text-sm text-gray-900">{{ $order->payment_method }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
                                    
                                    @if($order->status === 'pending')
                                        <button class="inline-flex items-center px-3 py-1 border border-green-300 text-xs font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 update-status-btn transition-colors duration-200" 
                                                data-order-id="{{ $order->id }}" data-status="accepted">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Accept
                                        </button>
                                        <button class="inline-flex items-center px-3 py-1 border border-red-300 text-xs font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 update-status-btn transition-colors duration-200" 
                                                data-order-id="{{ $order->id }}" data-status="cancelled">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Cancel
                                        </button>
                                    @elseif($order->status === 'accepted')
                                        <button class="inline-flex items-center px-3 py-1 border border-orange-300 text-xs font-medium rounded-md text-orange-700 bg-orange-50 hover:bg-orange-100 update-status-btn transition-colors duration-200" 
                                                data-order-id="{{ $order->id }}" data-status="preparing">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Preparing
                                        </button>
                                    @elseif($order->status === 'preparing')
                                        <button class="inline-flex items-center px-3 py-1 border border-green-300 text-xs font-medium rounded-md text-green-700 bg-green-50 hover:bg-green-100 update-status-btn transition-colors duration-200" 
                                                data-order-id="{{ $order->id }}" data-status="ready">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Ready
                                        </button>
                                    @elseif($order->status === 'ready')
                                        <button class="inline-flex items-center px-3 py-1 border border-blue-300 text-xs font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 update-status-btn transition-colors duration-200" 
                                                data-order-id="{{ $order->id }}" data-status="served">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Served
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-500">No orders found</p>
                                    <p class="text-sm text-gray-400">Orders will appear here when customers place them</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="lg:hidden">
                <div class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                        <!-- Order Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-3">
                                    <span class="text-orange-600 font-bold">#{{ substr($order->id, -3) }}</span>
                                </div>
                                <div>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-lg font-semibold text-gray-900 hover:text-orange-600">
                                        Order #{{ $order->id }}
                                    </a>
                                    <div class="text-sm text-gray-600">{{ $order->customer_name }}</div>
                                </div>
                            </div>
                            <span class="order-status px-3 py-1 text-xs font-semibold rounded-full
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                @elseif($order->status === 'accepted') bg-blue-100 text-blue-800 border border-blue-200
                                @elseif($order->status === 'preparing') bg-orange-100 text-orange-800 border border-orange-200
                                @elseif($order->status === 'ready') bg-green-100 text-green-800 border border-green-200
                                @elseif($order->status === 'served') bg-gray-100 text-gray-800 border border-gray-200
                                @else bg-red-100 text-red-800 border border-red-200
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <!-- Order Details -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Table & Guests
                                </div>
                                <div class="font-semibold text-gray-900">Table {{ $order->table->table_number }}</div>
                                <div class="text-xs text-gray-500">{{ $order->guest_count }} guests</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center text-sm text-gray-600 mb-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2z"/>
                                    </svg>
                                    Payment
                                </div>
                                <div class="font-semibold text-gray-900 capitalize">{{ $order->payment_method }}</div>
                                <div class="text-xs text-gray-500">{{ $order->created_at->format('M d, H:i') }}</div>
                            </div>
                        </div>

                        <!-- Items and Amount -->
                        <div class="bg-orange-50 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium text-gray-700">{{ $order->items->count() }} items</div>
                                    <div class="text-xs text-gray-600">
                                        @foreach($order->items->take(2) as $item)
                                            {{ $item->product->name }}@if(!$loop->last), @endif
                                        @endforeach
                                        @if($order->items->count() > 2)
                                            <span class="text-gray-400">+{{ $order->items->count() - 2 }} more</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-orange-600">₹{{ number_format($order->total_amount, 0) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Details
                            </a>
                            
                            @if($order->status === 'pending')
                                <button class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 update-status-btn transition-colors duration-200" 
                                        data-order-id="{{ $order->id }}" data-status="accepted">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Accept Order
                                </button>
                                <button class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 update-status-btn transition-colors duration-200" 
                                        data-order-id="{{ $order->id }}" data-status="cancelled">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancel
                                </button>
                            @elseif($order->items->count() === 0) 
                                <button class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 border border-red-300 text-sm font-medium rounded-lg text-red-700 bg-red-50 hover:bg-red-100 update-status-btn transition-colors duration-200" 
                                        data-order-id="{{ $order->id }}" data-status="cancelled">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancel
                                </button>     
                            @elseif($order->status === 'accepted')
                                <button class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 border border-orange-300 text-sm font-medium rounded-lg text-orange-700 bg-orange-50 hover:bg-orange-100 update-status-btn transition-colors duration-200" 
                                        data-order-id="{{ $order->id }}" data-status="preparing">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Start Preparing
                                </button>
                            @elseif($order->status === 'preparing')
                                <button class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 update-status-btn transition-colors duration-200" 
                                        data-order-id="{{ $order->id }}" data-status="ready">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Mark Ready
                                </button>
                            @elseif($order->status === 'ready')
                                <button class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 border border-blue-300 text-sm font-medium rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 update-status-btn transition-colors duration-200" 
                                        data-order-id="{{ $order->id }}" data-status="served">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Mark Served
                                </button>
                              
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-xl font-medium text-gray-500 mb-2">No orders found</p>
                        <p class="text-sm text-gray-400">Orders will appear here when customers place them</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Enhanced Pagination -->
        @if($orders->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-3">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- New Order Alert Modal -->
<div id="new-order-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 mb-4">
                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-12"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">New Order Received!</h3>
            <p class="text-sm text-gray-500 mb-4" id="new-order-details">Order #<span id="new-order-id"></span> from Table <span id="new-order-table"></span></p>
            <div class="flex space-x-3">
                <button id="dismiss-alert" class="flex-1 btn-secondary">Dismiss</button>
                <button id="view-order" class="flex-1 btn-primary">View Order</button>
                <button class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 update-status-btn transition-colors duration-200" 
                                        data-order-id="" data-status="accepted">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Accept Order
                                </button>
            </div>
        </div>
    </div>
</div>

<audio src="{{ asset("sounds/notification.mp3")}}" preload="auto" id="newOrderSound"></audio>
@endsection

@push('scripts')
<script>

// document.addEventListener('DOMContentLoaded', () => {
//     // We no longer need the button element
//     // const enableBtn = document.getElementById('enableAlertsBtn');

//     const audio = document.getElementById('newOrderSound');

//     // Define the function that will be executed on mouse movement
//     const handleMouseMove = () => {
//         if (audio) {
//             // 1. Attempt a play/pause sequence on the user gesture (mouse move).
//             audio.play().then(() => {
//                 audio.pause();
//                 audio.currentTime = 0;
//                 console.log("Audio permissions granted successfully via mouse movement.");

//                 // 2. IMPORTANT: Remove the listener after successful execution
//                 document.removeEventListener('mousemove', handleMouseMove);

//             }).catch(e => {
//                 // Audio still failed, even with a user gesture.
//                 console.error("Failed to prime audio. Browser blocked the attempt.", e);
//             });
//         }
//     };

//     // Attach the 'mousemove' listener to the entire document
//     document.addEventListener('mousemove', handleMouseMove);

//     // Note: The { once: true } option is not always reliable across all browsers
//     // for document events, so explicitly using removeEventListener is more robust.
// });

//      this is right code for audio play on user interaction
// document.addEventListener('DOMContentLoaded', () => {
//     const enableBtn = document.getElementById('enableAlertsBtn');
//     const audio = document.getElementById('newOrderSound');
    
//     if (enableBtn && audio) {
//         enableBtn.addEventListener('click', () => {
//             // 1. Attempt a play/pause sequence on the user click.
//             // This satisfies the browser's requirement for a user gesture.
//             audio.play().then(() => {
//                 audio.pause();
//                 audio.currentTime = 0;
//                 console.log("Audio permissions granted successfully.");
                
//                 // 2. Hide or disable the button once permission is granted
//                 enableBtn.style.display = 'none';
                
//             }).catch(e => {
//                 console.error("Failed to prime audio, please check browser settings.", e);
//             });
//         }, { once: true });
//     }
// });




// document.addEventListener('DOMContentLoaded', () => {
//     const enableBtn = document.getElementById('enableAlertsBtn');
//     const audio = document.getElementById('newOrderSound');
//     const PERMISSION_KEY = 'audioPermissionGranted';
    
//     // Function to "prime" the audio and set the local storage flag
//     const grantPermission = () => {
//         if (audio) {
//             audio.play().then(() => {
//                 audio.pause();
//                 audio.currentTime = 0;
                
//                 // 🔑 1. SET THE FLAG: Store the permission status
//                 localStorage.setItem(PERMISSION_KEY, 'true');
//                 console.log("Audio permissions granted and saved.");
                
//                 // Hide the button
//                 if (enableBtn) {
//                     enableBtn.style.display = 'none';
//                 }
                
//             }).catch(e => {
//                 console.error("Failed to prime audio:", e);
//             });
//         }
//     };

//     // 🔑 2. CHECK THE FLAG: Logic executed on every page load (including after refresh)
//     if (localStorage.getItem(PERMISSION_KEY) === 'true') {
//         // If the flag is set, try to grant permission silently without a click
//         // Note: This often works after a manual click has set the permission before.
//         grantPermission(); 
//     }
    
//     // 🔑 3. ATTACH LISTENER: If the button exists, attach the click handler
//     if (enableBtn) {
//         enableBtn.addEventListener('click', grantPermission, { once: true });
//     }
// });

$(document).ready(function() {
    // Enhanced auto-refresh with visual indicator
    function refresh() {
        if (!window.location.search) {
            // Show refresh indicator
            const indicator = $('<div class="fixed top-4 right-4 bg-blue-500 text-white px-3 py-2 rounded-lg text-sm z-50">Refreshing...</div>');
            $('body').append(indicator);
            
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    }; 
    // 30 seconds
    let refreshInterval = setInterval(function() {
        if (!window.location.search) {
            // Show refresh indicator
            const indicator = $('<div class="fixed top-4 right-4 bg-blue-500 text-white px-3 py-2 rounded-lg text-sm z-50">Refreshing...</div>');
            $('body').append(indicator);
            
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    }, 60000); // 60 seconds


    setInterval(updateDashboard, 5000); // 5 seconds
   //check for new orders every 15 seconds
     function updateDashboard() {
        $.get('{{ route("admin.live-data") }}')
            .done(function(data) {
                // Update pending orders count
                const currentCount = parseInt($('#pending-orders-count').text());
                const newCount = data.pending_orders.length;
                
                $('#pending-orders-count').text(newCount+' Pending Orders');
                
                // Show alert for new orders
                if (newCount > currentCount && data.new_orders_count > 0) {
                    showNewOrderAlert(data.pending_orders[0]);
                }
                
                // Update recent orders table
                
            })
            .fail(function() {
                console.log('Failed to fetch live data');
            });
    }


    
    function showNewOrderAlert(order) {
        $('#new-order-id').text(order.id);
        $('#new-order-table').text(order.table.table_number);
        $('#new-order-modal').removeClass('hidden');
        var acceptButton = $('.update-status-btn[data-status="accepted"]');
        acceptButton.attr('data-order-id', order.id);

      // 🔊 ROBUST AUDIO PLAYBACK CODE
    const audio = document.getElementById('newOrderSound');

    if (audio) {
        // Function to handle the actual playback
        const playAudio = () => {
            audio.currentTime = 0; // Reset to the start for instant replay
            audio.play().catch(error => {
                // Log warning for Autoplay Policy errors (if the user hasn't clicked anything yet)
                console.warn("Audio playback failed (Autoplay Policy?):", error.name);
            });
        };

        // Check if the media is already loaded (readyState 4 means it's ready)
        // readyState 4 (HAVE_ENOUGH_DATA) or 3 (HAVE_FUTURE_DATA)
        if (audio.readyState >= 3) {
            playAudio();
        } else {
            // If not loaded, wait for the 'canplay' event.
            // We use { once: true } to automatically remove the listener after it fires.
            audio.addEventListener('canplay', playAudio, { once: true });
        }
    } else {
        console.error("Audio element with ID 'newOrderSound' not found.");
    }

        // Auto-dismiss after 10 seconds
        setTimeout(function() {
            $('#new-order-modal').addClass('hidden');
            location.reload();
        }, 10000);
    }



    // Modal controls
    $('#dismiss-alert, #new-order-modal').click(function(e) {
        if (e.target === this) {
            $('#new-order-modal').addClass('hidden');
        }
    });
    
    $('#view-order').click(function() {
        const orderId = $('#new-order-id').text();
        window.location.href = `/admin/orders/${orderId}`;
    });




    
    // Enhanced status update with better feedback
    $('.update-status-btn').click(function() {
        const button = $(this);
        const orderId = button.data('order-id');
        const status = button.data('status');
        
        // Store original content
        const originalContent = button.html();
        
        // Show loading state
        button.prop('disabled', true)
              .html(`
                  <svg class="animate-spin w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Updating...
              `);
        
        $.post(`/admin/orders/${orderId}/status`, {
            status: status
        })
        .done(function(response) {
            if (response.success) {
                showToast(response.message, 'success');
                
                // Update status badge immediately
                const statusBadge = button.closest('.order-row, div').find('.order-status');
                statusBadge.removeClass()
                          .addClass(`order-status px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200`)
                          .text(status.charAt(0).toUpperCase() + status.slice(1));
                
                // Reload after short delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        })
        .fail(function() {
            showToast('Failed to update order status. Please try again.', 'error');
            button.html(originalContent).prop('disabled', false);
        });
    });
    
    // Keyboard shortcuts
    $(document).keydown(function(e) {
        // Press 'R' to refresh
        if (e.key === 'r' || e.key === 'R') {
            if (!e.ctrlKey && !e.metaKey) {
                e.preventDefault();
                location.reload();
            }
        }
    });
});

// Enhanced toast function
function showToast(message, type = 'success') {
    const toastId = 'toast-' + Date.now();
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' ? 
        '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>' :
        '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
    
    const toast = $(`
        <div id="${toastId}" class="${bgColor} text-white px-6 py-4 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 flex items-center">
            ${icon}
            <span>${message}</span>
        </div>
    `);
    
    $('#toast-container').append(toast);
    
    setTimeout(() => {
        $(`#${toastId}`).removeClass('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        $(`#${toastId}`).addClass('translate-x-full');
        setTimeout(() => $(`#${toastId}`).remove(), 300);
    }, 4000);
}
</script>
@endpush
