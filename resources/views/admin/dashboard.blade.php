@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600">Welcome back! Here's what's happening at your restaurant today.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Today's Sales -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Sales</p>
                    <p class="text-3xl font-bold text-green-600">₹{{ number_format($todaySales, 0) }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                    <p class="text-3xl font-bold text-orange-600" id="pending-orders-count">{{ $pendingOrders }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Orders</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalOrders }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Tables -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Tables</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $activeTables }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders and Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="recent-orders-tbody">
                        @foreach($recentOrders as $order)
                        <tr class="order-row" data-order-id="{{ $order->id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->customer_name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $order->table->table_number }}
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($order->status === 'pending')
                                <div class="flex space-x-2">
                                    <button class="text-green-600 hover:text-green-900 update-status-btn" 
                                            data-order-id="{{ $order->id }}" data-status="accepted">Accept</button>
                                    <button class="text-red-600 hover:text-red-900 update-status-btn" 
                                            data-order-id="{{ $order->id }}" data-status="cancelled">Cancel</button>
                                </div>
                                @elseif($order->status === 'ready')
                                <button class="text-blue-600 hover:text-blue-900 update-status-btn" 
                                        data-order-id="{{ $order->id }}" data-status="served">Mark Served</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Today's Top Items</h2>
            </div>
            
            <div class="p-6">
                @if($topProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($topProducts as $product)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-orange-600 font-semibold text-sm">{{ $loop->iteration }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">{{ $product->total_sold }} sold</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No orders today</p>
                @endif
            </div>
        </div>
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Real-time updates every 5 seconds
    setInterval(function() {
        updateDashboard();
    }, 5000);
    
    function updateDashboard() {
        $.get('{{ route("admin.live-data") }}')
            .done(function(data) {
                // Update pending orders count
                const currentCount = parseInt($('#pending-orders-count').text());
                const newCount = data.pending_orders.length;
                
                $('#pending-orders-count').text(newCount);
                
                // Show alert for new orders
                if (newCount > currentCount && data.new_orders_count > 0) {
                    showNewOrderAlert(data.pending_orders[0]);
                }
                
                // Update recent orders table
                updateRecentOrdersTable(data.pending_orders);
            })
            .fail(function() {
                console.log('Failed to fetch live data');
            });
    }
    
    function showNewOrderAlert(order) {
        $('#new-order-id').text(order.id);
        $('#new-order-table').text(order.table.table_number);
        $('#new-order-modal').removeClass('hidden');
        
        // Auto-dismiss after 10 seconds
        setTimeout(function() {
            $('#new-order-modal').addClass('hidden');
        }, 10000);
    }
    
    function updateRecentOrdersTable(orders) {
        // Update existing rows with new status
        orders.forEach(function(order) {
            const row = $(`.order-row[data-order-id="${order.id}"]`);
            if (row.length) {
                updateOrderRow(row, order);
            }
        });
    }
    
    function updateOrderRow(row, order) {
        const statusSpan = row.find('.order-status');
        const actionCell = row.find('td:last-child');
        
        // Update status
        statusSpan.removeClass().addClass('order-status px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full');
        
        switch(order.status) {
            case 'pending':
                statusSpan.addClass('bg-yellow-100 text-yellow-800');
                break;
            case 'accepted':
                statusSpan.addClass('bg-blue-100 text-blue-800');
                break;
            case 'preparing':
                statusSpan.addClass('bg-orange-100 text-orange-800');
                break;
            case 'ready':
                statusSpan.addClass('bg-green-100 text-green-800');
                break;
            case 'served':
                statusSpan.addClass('bg-gray-100 text-gray-800');
                break;
        }
        
        statusSpan.text(order.status.charAt(0).toUpperCase() + order.status.slice(1));
        
        // Update action buttons
        let actionButtons = '';
        if (order.status === 'pending') {
            actionButtons = `
                <div class="flex space-x-2">
                    <button class="text-green-600 hover:text-green-900 update-status-btn" 
                            data-order-id="${order.id}" data-status="accepted">Accept</button>
                    <button class="text-red-600 hover:text-red-900 update-status-btn" 
                            data-order-id="${order.id}" data-status="cancelled">Cancel</button>
                </div>
            `;
        } else if (order.status === 'ready') {
            actionButtons = `
                <button class="text-blue-600 hover:text-blue-900 update-status-btn" 
                        data-order-id="${order.id}" data-status="served">Mark Served</button>
            `;
        }
        
        actionCell.html(actionButtons);
    }
    
    // Update order status
    $(document).on('click', '.update-status-btn', function() {
        const orderId = $(this).data('order-id');
        const status = $(this).data('status');
        const button = $(this);
        
        button.prop('disabled', true).text('Updating...');
        
        $.post(`/admin/orders/${orderId}/status`, {
            status: status
        })
        .done(function(response) {
            if (response.success) {
                showToast(response.message);
                updateDashboard(); // Refresh data
            }
        })
        .fail(function() {
            showToast('Failed to update order status', 'error');
        })
        .always(function() {
            button.prop('disabled', false);
        });
    });
    
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
});
</script>
@endpush
