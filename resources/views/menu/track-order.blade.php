@extends('layouts.app')

@section('title', 'Track Your Order')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="px-4 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-900">Track Your Order</h1>
                <a href="{{ route('menu.index') }}" class="text-orange-500 hover:text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <div class="p-4">
        @if(isset($order))
            <!-- Order Found - Show Tracking -->
            <div class="max-w-2xl mx-auto">
                <!-- Order Header -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="text-center">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Order #{{ $order->id }}</h2>
                        <p class="text-gray-600">{{ $order->customer_name }} • Table {{ $order->table->table_number }}</p>
                        <p class="text-sm text-gray-500">Placed on {{ $order->created_at->format('M d, Y \a\t H:i') }}</p>
                    </div>
                </div>

                <!-- Order Progress -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
                    
                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <div class="flex justify-between text-xs text-gray-500 mb-2">
                            <span>Order Placed</span>
                            <span>Preparing</span>
                            <span>Ready</span>
                            <span>Served</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="progress-bar" class="bg-orange-500 h-2 rounded-full transition-all duration-500" 
                                 style="width: {{ $progressPercentage }}%"></div>
                        </div>
                    </div>

                    <!-- Current Status -->
                    <div class="text-center">
                        <div id="current-status" class="inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'accepted') bg-blue-100 text-blue-800
                            @elseif($order->status === 'preparing') bg-orange-100 text-orange-800
                            @elseif($order->status === 'ready') bg-green-100 text-green-800
                            @elseif($order->status === 'served') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif">
                            <span id="status-icon" class="mr-2">
                                @if($order->status === 'pending') ⏳
                                @elseif($order->status === 'accepted') ✅
                                @elseif($order->status === 'preparing') 👨‍🍳
                                @elseif($order->status === 'ready') 🍽️
                                @elseif($order->status === 'served') ✨
                                @else ❌
                                @endif
                            </span>
                            <span id="status-text">{{ ucfirst($order->status) }}</span>
                        </div>
                        
                        @if($order->status === 'preparing')
                            <p class="text-sm text-gray-600 mt-2" id="estimated-time">
                                Estimated time: {{ $estimatedTime }} minutes
                            </p>
                        @elseif($order->status === 'ready')
                            <p class="text-sm text-green-600 mt-2 font-medium">
                                🎉 Your order is ready! Please collect from the counter.
                            </p>
                        @elseif($order->status === 'served')
                            <p class="text-sm text-gray-600 mt-2">
                                Thank you for dining with us! 😊
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                    
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <div class="flex items-center">
                                @if($item->product->image)
                                    <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" 
                                         class="w-12 h-12 rounded-lg object-cover mr-4">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
                                    <div class="flex items-center space-x-2 mt-1">
                                        @if($item->product->is_veg)
                                            <div class="veg-indicator"></div>
                                        @else
                                            <div class="non-veg-indicator"></div>
                                        @endif
                                        <span class="text-xs text-gray-500">Qty: {{ $item->quantity }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">₹{{ number_format($item->subtotal, 0) }}</div>
                                <span class="item-status text-xs px-2 py-1 rounded-full font-medium
                                    @if($item->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($item->status === 'preparing') bg-orange-100 text-orange-800
                                    @elseif($item->status === 'ready') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif" data-item-id="{{ $item->id }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Order Total -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between text-lg font-semibold text-gray-900">
                            <span>Total Amount:</span>
                            <span>₹{{ number_format($order->total_amount, 0) }}</span>
                        </div>
                        <div class="text-sm text-gray-500 mt-1">
                            Payment: {{ ucfirst($order->payment_method) }}
                        </div>
                    </div>
                </div>

                @if($order->notes)
                <!-- Special Instructions -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Special Instructions</h3>
                    <p class="text-gray-700">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        @else
            <!-- No Order - Show Search Form -->
            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 mb-2">Track Your Order</h2>
                        <p class="text-gray-600">Enter your order ID to check the status</p>
                    </div>
                    
                    <form id="track-form">
                        <div class="space-y-4">
                            <div>
                                <label for="order_id" class="block text-sm font-medium text-gray-700 mb-2">Order ID</label>
                                <input type="number" id="order_id" name="order_id" required
                                       class="form-input" placeholder="Enter your order ID (e.g., 123)">
                            </div>
                            
                            <button type="submit" class="w-full btn-primary">
                                Track Order
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-500">Don't have your order ID?</p>
                        <a href="{{ route('menu.index') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium">
                            Go back to menu
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    @if(isset($order))
        // Start real-time updates for order tracking
        const orderId = {{ $order->id }};
        
        function updateOrderStatus() {
            $.get(`/api/order/${orderId}/status`)
                .done(function(data) {
                    // Update status text and icon
                    $('#status-text').text(data.status_text);
                    
                    // Update status icon
                    let icon = '';
                    switch(data.status) {
                        case 'pending': icon = '⏳'; break;
                        case 'accepted': icon = '✅'; break;
                        case 'preparing': icon = '👨‍🍳'; break;
                        case 'ready': icon = '🍽️'; break;
                        case 'served': icon = '✨'; break;
                        default: icon = '❌';
                    }
                    $('#status-icon').text(icon);
                    
                    // Update progress bar
                    $('#progress-bar').css('width', data.progress_percentage + '%');
                    
                    // Update status badge classes
                    const statusClasses = {
                        'pending': 'bg-yellow-100 text-yellow-800',
                        'accepted': 'bg-blue-100 text-blue-800',
                        'preparing': 'bg-orange-100 text-orange-800',
                        'ready': 'bg-green-100 text-green-800',
                        'served': 'bg-gray-100 text-gray-800',
                        'cancelled': 'bg-red-100 text-red-800'
                    };
                    
                    $('#current-status').removeClass().addClass('inline-flex items-center px-4 py-2 rounded-full text-lg font-semibold ' + statusClasses[data.status]);
                    
                    // Update item statuses
                    data.items.forEach(function(item) {
                        const itemStatusEl = $(`.item-status[data-item-id="${item.id}"]`);
                        // alert(itemStatusEl);
                        if (itemStatusEl.length) {
                            itemStatusEl.text(item.status_text);
                            
                            const itemStatusClasses = {
                                'pending': 'bg-yellow-100 text-yellow-800',
                                'preparing': 'bg-orange-100 text-orange-800',
                                'ready': 'bg-green-100 text-green-800',
                                'served': 'bg-gray-100 text-gray-800'
                            };
                            
                            itemStatusEl.removeClass().addClass('item-status text-xs px-2 py-1 rounded-full font-medium ' + itemStatusClasses[item.status]);
                        }
                    });
                    
                    // Show notification for status changes
                    if (data.status === 'ready') {
                        showToast('🎉 Your order is ready! Please collect from the counter.');
                    } else if (data.status === 'served') {
                        showToast('✨ Thank you for dining with us!');
                    }
                })
                .fail(function() {
                    console.log('Failed to update order status');
                });
        }
        
        // Update every 5 seconds
        setInterval(updateOrderStatus, 5000);
       
    @else
        // Handle order tracking form
        $('#track-form').submit(function(e) {
            e.preventDefault();
            
            const orderId = $('#order_id').val();
            
            if (!orderId) {
                showToast('Please enter an order ID', 'error');
                return;
            }
            
            // Redirect to track order page with ID
            window.location.href = `/track-order/${orderId}`;
        });
    @endif
});
</script>
@endpush
