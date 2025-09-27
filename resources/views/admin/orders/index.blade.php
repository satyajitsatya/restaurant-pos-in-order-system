@extends('layouts.admin')

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
                            <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
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
@endpush
