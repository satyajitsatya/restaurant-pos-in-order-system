@extends('layouts.admin')

@section('title', 'Order Details')
@section('page-title', 'Order #' . $order->id)

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                <p class="text-gray-600">Placed on {{ $order->created_at->format('M d, Y \a\t H:i') }}</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn-secondary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Orders
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                            <div class="flex items-center justify-between py-4 border-b border-gray-100 last:border-0">
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
                                        <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                        <div class="flex items-center space-x-2 mt-1">
                                            @if($item->product->is_veg)
                                                <div class="veg-indicator"></div>
                                            @else
                                                <div class="non-veg-indicator"></div>
                                            @endif
                                            <span class="text-xs text-gray-500 capitalize">{{ $item->product->spice_level }}</span>
                                            <span class="text-xs text-gray-500">{{ $item->product->prep_time }} mins</span>
                                        </div>
                                        <div class="mt-1">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                @if($item->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($item->status === 'preparing') bg-orange-100 text-orange-800
                                                @elseif($item->status === 'ready') bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full text-xs font-medium transition-colors cancel-btn"  data-itemid="{{ $item->id }}" data-order-id="{{ $item->order->id }}">Cancel item</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="text-sm text-gray-900">Qty: {{ $item->quantity }}</div>
                                    <div class="text-sm text-gray-500">₹{{ number_format($item->price, 0) }} each</div>
                                    <div class="font-medium text-gray-900">₹{{ number_format($item->subtotal, 0) }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Order Total -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-between text-lg font-semibold text-gray-900">
                                <span>Total Amount:</span>
                                <span>₹{{ number_format($order->total_amount, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Customer Information</h2>
                    </div>
                    
                    <div class="p-6">
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Customer Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_name }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Table Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->table->table_number }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Guest Count</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->guest_count }} {{ Str::plural('person', $order->guest_count) }}</dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                                <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $order->payment_method }}</dd>
                            </div>
                            
                            @if($order->notes)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Special Instructions</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->notes }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Order Status</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <span class="px-4 py-2 text-lg font-semibold rounded-full
                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'accepted') bg-blue-100 text-blue-800
                                @elseif($order->status === 'preparing') bg-orange-100 text-orange-800
                                @elseif($order->status === 'ready') bg-green-100 text-green-800
                                @elseif($order->status === 'served') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        
                        <!-- Status Actions -->
                        @if($order->status === 'pending')
                        <div class="flex space-x-2">
                            <button class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors update-status-btn" 
                                    data-order-id="{{ $order->id }}" data-status="accepted">
                                Accept Order
                            </button>
                            <button class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors update-status-btn" 
                                    data-order-id="{{ $order->id }}" data-status="cancelled">
                                Cancel Order
                            </button>
                        </div>
                        @elseif($order->status === 'accepted')
                        <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors update-status-btn" 
                                data-order-id="{{ $order->id }}" data-status="preparing">
                            Mark as Preparing
                        </button>
                        @elseif($order->status === 'preparing')
                        <button class="w-full bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors update-status-btn" 
                                data-order-id="{{ $order->id }}" data-status="ready">
                            Mark as Ready
                        </button>
                        @elseif($order->status === 'ready')
                        <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors update-status-btn" 
                                data-order-id="{{ $order->id }}" data-status="served">
                            Mark as Served
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.update-status-btn').click(function() {
        const button = $(this);
        const orderId = button.data('order-id');
        const status = button.data('status');
        
        if (!confirm('Are you sure you want to update the order status?')) {
            return;
        }
        
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


$('.cancel-btn').click(function() {
    const button = $(this);
    const itemId = button.data('itemid');
    const orderId = button.data('order-id');
    
    if (!confirm('Are you sure you want to cancel this item?')) {
        return;
    }
    
    button.prop('disabled', true).text('Cancelling...');
    
    $.post(`/admin/orderItem/${itemId}/cancel`)
    .done(function(response) {
        if (response.success) {
            showToast(response.message);
            // Reload page to show updated status
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
        else {
            showToast(response.message, 'error');
        }
    })
    .fail(function() {
        showToast('Failed to cancel item', 'error');
    })
    .always(function() {
        button.prop('disabled', false).text('Cancel item');
    });
});
</script>
@endpush
