{{-- @extends('layouts.admin')

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
@endpush --}}









@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="p-6">
    <!-- Page Header with Date Filter -->
    <div class="mb-8">
        {{-- <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600">Welcome back! Here's what's happening at your restaurant.</p>
            </div>
            
            <!-- Date Filter -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <label for="date" class="text-sm font-medium text-gray-700">Date:</label>
                        <input type="date" id="date" name="date" 
                               value="{{ $selectedDate }}" 
                               max="{{ date('Y-m-d') }}"
                               class="form-input text-sm w-40">
                    </div>
                    <button type="submit" class="btn-primary text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    @if($selectedDate !== date('Y-m-d'))
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                            Reset to Today
                        </a>
                    @endif
                </form>
            </div>
        </div> --}}

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-6 lg:space-y-0">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-2 text-sm lg:text-base text-gray-600">Welcome back! Here's what's happening at your restaurant.</p>
        <button id="enableAlertsBtn" 
        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-200">
            Enable New Order Alerts Sound
        </button>
    </div>
    
    <!-- Enhanced Date Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 lg:p-6 w-full lg:w-auto">
        <form method="GET" action="{{ route('admin.dashboard') }}" 
              class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
            
            <!-- Date Input Section -->
            <div class="w-full sm:w-auto">
                <label for="date" class="block text-sm font-semibold text-gray-700 mb-2 sm:mb-0 sm:inline sm:mr-3">
                    <svg class="w-4 h-4 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Filter by Date:
                </label>
                <input type="date" id="date" name="date" 
                       value="{{ $selectedDate }}" 
                       max="{{ date('Y-m-d') }}"
                       class="form-input w-full sm:w-44 text-sm border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row w-full sm:w-auto space-y-3 sm:space-y-0 sm:space-x-3">
                <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Apply Filter
                </button>
                
                @if($selectedDate !== date('Y-m-d'))
                    <a href="{{ route('admin.dashboard') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset to Today
                    </a>
                @endif
            </div>
        </form>
        
        <!-- Selected Date Display (Mobile) -->
        <div class="mt-4 lg:hidden">
            <div class="flex items-center justify-between p-3 bg-orange-50 border border-orange-200 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-medium text-orange-800">
                        Showing data for: 
                        <span class="font-bold">
                            @if($selectedDate === date('Y-m-d'))
                                Today
                            @else
                                {{ date('M d, Y', strtotime($selectedDate)) }}
                            @endif
                        </span>
                    </span>
                    </div>
                    @if($selectedDate !== date('Y-m-d'))
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-200 text-orange-800">
                        Filtered
                    </span>
                   @endif
               </div>
             </div>
          </div>
       </div>

        
        <!-- Selected Date Info -->
        <div class="mt-4 text-sm text-gray-600">
            Showing data for: <span class="font-medium text-gray-900">{{ Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}</span>
            @if($selectedDate !== date('Y-m-d'))
                <span class="text-orange-600">({{ Carbon\Carbon::parse($selectedDate)->diffForHumans() }})</span>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Today's Sales -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Selected Date Sales</p>
                    <p class="text-3xl font-bold text-green-600">₹{{ number_format($todaySales, 0) }}</p>
                    @if($salesGrowth != 0)
                        <p class="text-xs mt-1 {{ $salesGrowth > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $salesGrowth > 0 ? '↗' : '↘' }} {{ number_format(abs($salesGrowth), 1) }}% vs previous day
                        </p>
                    @endif
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Orders (Real-time) -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                    <p class="text-3xl font-bold text-orange-600" id="pending-orders-count">{{ $pendingOrders }}</p>
                    <p class="text-xs text-gray-500 mt-1">Live count</p>
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
                    <p class="text-sm font-medium text-gray-600">Selected Date Orders</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalOrders }}</p>
                    @if($totalOrders > 0)
                        <p class="text-xs text-gray-500 mt-1">Avg: ₹{{ number_format($todaySales / $totalOrders, 0) }} per order</p>
                    @endif
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
                    <p class="text-xs text-gray-500 mt-1">Currently occupied</p>
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
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
                        @forelse($recentOrders as $order)
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
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No orders found for {{ Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Top Items - {{ Carbon\Carbon::parse($selectedDate)->format('M j') }}</h2>
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
                    <p class="text-gray-500 text-center py-8">No orders on this date</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly Analytics Section -->
    <div class="bg-white rounded-lg shadow-sm mb-8">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Monthly Sales Analytics</h2>
            
            <!-- Month Selector -->
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <input type="hidden" name="date" value="{{ $selectedDate }}">
                <label for="month" class="text-sm font-medium text-gray-700">Month:</label>
                <input type="month" id="month" name="month" 
                       value="{{ $selectedMonth }}" 
                       max="{{ date('Y-m') }}"
                       class="form-input text-sm w-40">
                <button type="submit" class="btn-primary text-sm">Update</button>
            </form>
        </div>
        
        <div class="p-6">
            <!-- Monthly Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-600">₹{{ number_format($monthlyAnalytics['total_sales'], 0) }}</p>
                    <p class="text-sm text-gray-600">Total Sales</p>
                    <p class="text-xs text-gray-500">{{ $monthlyAnalytics['month_name'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ number_format($monthlyAnalytics['total_orders']) }}</p>
                    <p class="text-sm text-gray-600">Total Orders</p>
                    <p class="text-xs text-gray-500">{{ $monthlyAnalytics['month_name'] }}</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-purple-600">₹{{ number_format($monthlyAnalytics['average_order_value'], 0) }}</p>
                    <p class="text-sm text-gray-600">Avg Order Value</p>
                    <p class="text-xs text-gray-500">{{ $monthlyAnalytics['month_name'] }}</p>
                </div>
            </div>

            <!-- Sales Chart -->
            <div class="mb-8">
                <h3 class="text-md font-semibold text-gray-900 mb-4">Daily Sales Trend</h3>
                <div class="h-64">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Top Categories -->
            @if($monthlyAnalytics['top_categories']->count() > 0)
            <div>
                <h3 class="text-md font-semibold text-gray-900 mb-4">Top Categories - {{ $monthlyAnalytics['month_name'] }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($monthlyAnalytics['top_categories'] as $category)
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <p class="font-medium text-gray-900">{{ $category->name }}</p>
                        <p class="text-2xl font-bold text-orange-600">₹{{ number_format($category->total_sales, 0) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- New Order Alert Modal -->
{{-- <div id="new-order-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
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
</div> --}}


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

            <div class="flex justify-between items-center border-t border-b border-gray-200 py-3 mb-4">
                <div id="new-order-data-display" class="flex-1 text-left">
                    </div>
                <div class="text-right">
                    <div id="new-order-total-amount" class="text-2xl font-bold text-orange-600"></div>
                </div>
            </div>
            <div class="flex space-x-3 mt-6">
                <button id="view-order" class="flex-1 btn-primary">View Order</button>
                <button id="accept-btn" class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2 border border-green-300 text-sm font-medium rounded-lg text-green-700 bg-green-50 hover:bg-green-100 update-status-btn transition-colors duration-200" 
                    data-order-id="" data-status="accepted">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Accept Order
                </button>
            </div>
            <button id="dismiss-alert" class="mt-4 text-sm text-gray-500 hover:text-gray-700">Dismiss Alert</button>
        </div>
    </div>
</div>









<audio src="{{ asset("sounds/notification.mp3")}}" preload="auto" id="newOrderSound"></audio>
@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    //  this is right code for audio play on user interaction
document.addEventListener('DOMContentLoaded', () => {
    const enableBtn = document.getElementById('enableAlertsBtn');
    const audio = document.getElementById('newOrderSound');
    
    if (enableBtn && audio) {
        enableBtn.addEventListener('click', () => {
        // $("#enableAlertsBtn").click(function() {
            // 1. Attempt a play/pause sequence on the user click.
            // This satisfies the browser's requirement for a user gesture.
            audio.play().then(() => {
                audio.pause();
                audio.currentTime = 0;
                console.log("Audio permissions granted successfully.");
                
                // 2. Hide or disable the button once permission is granted
                enableBtn.style.display = 'none';
                
            }).catch(e => {
                console.error("Failed to prime audio, please check browser settings.", e);
            });
        }, { once: true });
    }
});





$(document).ready(function() {
    // Initialize Sales Chart
    initializeSalesChart();
    
    // Real-time updates every 5 seconds
    setInterval(function() {
        updateDashboard();
    }, 5000);
    
    function initializeSalesChart() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesData = @json($dailySalesData);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.map(item => item.date),
                datasets: [{
                    label: 'Daily Sales (₹)',
                    data: salesData.map(item => item.sales),
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#f97316',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }
    // console.log("this is dashboard page");
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
        var acceptButton = $('#accept-btn');
        acceptButton.attr('data-order-id', order.id);

        // 3. Set the total amount (using 'en-IN' locale for Indian Rupee format)
    // Make sure 'total_amount' is available and a number.
    const amount = parseFloat(order.total_amount);
    const formattedAmount = '₹' + amount.toLocaleString('en-IN', { 
        maximumFractionDigits: 0, 
        minimumFractionDigits: 0 
    });
    $('#new-order-total-amount').text(formattedAmount);

    // 4. Generate and set the item details HTML (Blade-like logic in JS)
    const items = order.items || [];
    const itemCount = items.length;
    let itemsHtml = '';

    // Item Count Display
    itemsHtml += '<div class="text-sm font-medium text-gray-700">' + itemCount + ' items</div>';
    
    // Item Names Display (limited to first 2)
    itemsHtml += '<div class="text-xs text-gray-600">';
    
    const maxItemsToShow = 2;
    const itemsToDisplay = items.slice(0, maxItemsToShow);
    
    // Loop through the first two items
    for (let i = 0; i < itemsToDisplay.length; i++) {
        const item = itemsToDisplay[i];
        // Assuming 'item.product' object exists and has 'name'
        itemsHtml += (item.product ? item.product.name : 'Unknown Item');
        
        // Add a comma and space if it's not the last item in the *displayed* list
        if (i < itemsToDisplay.length - 1) {
            itemsHtml += ', ';
        }
    }

    // Add "+N more" if there are more than 2 items
    if (itemCount > maxItemsToShow) {
        const moreCount = itemCount - maxItemsToShow;
        itemsHtml += ' <span class="text-gray-400">+' + moreCount + ' more</span>';
    }
    
    itemsHtml += '</div>';
    
    // Insert the generated HTML into the display area
    $('#new-order-data-display').html(itemsHtml);                                                                                                                                                                                                                                      


        $('#new-order-modal').removeClass('hidden');

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
        }, 10000);
    }





     // Enhanced status update with better feedback
    $('#accept-btn').click(function() {
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


                 button.prop('disabled', true)
              .html(`
                  <svg class="animate-spin w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Accepted
              `);
                updateDashboard(); // Refresh data
                 setTimeout(() => {
                    button.prop("disabled", false).html(originalContent);
                    $('#new-order-modal').addClass('hidden');
                }, 2000);

 
                
                // Update status badge immediately
                // const statusBadge = button.closest('.order-row, div').find('.order-status');
                // statusBadge.removeClass()
                //           .addClass(`order-status px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200`)
                //           .text(status.charAt(0).toUpperCase() + status.slice(1));
                
                // Reload after short delay
                // setTimeout(() => {
                //     location.reload();
                // }, 1500);
            }
        })
        .fail(function() {
            showToast('Failed to update order status. Please try again.', 'error');
            button.html(originalContent).prop('disabled', false);
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
                button.prop('disabled', false).text('Updated');
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
    
 

    // Auto-submit form when date changes
    $('#date').change(function() {
        $(this).closest('form').submit();
    });

    // Auto-submit form when month changes
    $('#month').change(function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush
