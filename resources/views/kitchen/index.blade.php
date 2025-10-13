@extends('layouts.kitchen')

@section('title', 'Kitchen Dashboard')

@section('content')
<div class="min-h-screen bg-gray-900 text-white">
    <!-- Header -->
    <header class="bg-gray-800 shadow-lg">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Kitchen Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <div class="text-sm">
                        <span class="text-gray-300">Active Orders:</span>
                        <span class="text-yellow-400 font-bold text-lg" id="active-orders-count">{{ $activeOrders->count() }}</span>
                    </div>
                    <div>
                        <button id="refresh-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Refresh
                        </button>
                    </div>
                    <div class="text-sm">
                        <span class="text-gray-300">Last Updated:</span>
                        <span class="text-green-400" id="last-updated">{{ now()->format('H:i:s') }}</span>
                    </div>
                </div>   
            </div>
        </div>
    </header>

    <!-- Kitchen Orders Grid -->
    <div class="p-6">
        @if($groupedOrders->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6" id="orders-grid">
                @foreach($groupedOrders as $tableNumber => $orders)
                    @foreach($orders as $order)
                        <div class="bg-gray-800 rounded-lg border border-gray-700 shadow-lg order-card" 
                             data-order-id="{{ $order->id }}">
                            
                            <!-- Order Header -->
                            <div class="bg-gradient-to-r from-orange-600 to-red-600 rounded-t-lg px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold">Table {{ $tableNumber }}</h3>
                                        <p class="text-sm opacity-90">Order #{{ $order->id }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm opacity-90">{{ $order->customer_name }}</p>
                                        <p class="text-xs opacity-75">{{ $order->created_at->format('H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="p-4">
                                <div class="space-y-3">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg border border-gray-600 order-item" 
                                             data-item-id="{{ $item->id }}">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-white">{{ $item->product->name }}</h4>
                                                <div class="flex items-center space-x-4 mt-1">
                                                    <span class="text-yellow-400 font-bold">Qty: {{ $item->quantity }}</span>
                                                    @if($item->product->spice_level !== 'mild')
                                                        <span class="px-2 py-1 rounded-full text-xs font-medium
                                                               @if($item->product->spice_level === 'medium') bg-yellow-600 text-yellow-100
                                                               @else bg-red-600 text-red-100 @endif">
                                                            {{ ucfirst($item->product->spice_level) }}
                                                        </span>
                                                    @endif
                                                    @if(!$item->product->is_veg)
                                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-600 text-red-100">
                                                            Non-Veg
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Status Buttons -->
                                            <div class="flex space-x-2">
                                                @if($item->status === 'pending')
                                                    <button class="px-3 py-1 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-medium transition-colors update-item-status" 
                                                            data-item-id="{{ $item->id }}" data-status="preparing">
                                                        Start
                                                    </button>
                                                @elseif($item->status === 'preparing')
                                                    <button class="px-3 py-1 bg-green-600 hover:bg-green-700 rounded-lg text-sm font-medium transition-colors update-item-status" 
                                                            data-item-id="{{ $item->id }}" data-status="ready">
                                                        Ready
                                                    </button>
                                                    <span class="px-3 py-1 bg-orange-600 rounded-lg text-sm font-medium">
                                                        Cooking
                                                    </span>
                                                @elseif($item->status === 'ready')
                                                    <span class="px-3 py-1 bg-green-600 rounded-lg text-sm font-medium">
                                                        ✓ Ready
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Order Actions -->
                                <div class="mt-4 pt-4 border-t border-gray-600">
                                    @php
                                        $allItemsReady = $order->items->every(function($item) {
                                            return $item->status === 'ready';
                                        });
                                    @endphp

                                    @if($allItemsReady)
                                        <div class="bg-green-600 text-white px-4 py-2 rounded-lg text-center font-bold">
                                            🍽️ Order Complete - Ready to Serve!
                                        </div>
                                    @else
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-300 text-sm">
                                                {{ $order->items->where('status', 'ready')->count() }} / {{ $order->items->count() }} items ready
                                            </span>
                                            <button class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-sm font-medium transition-colors mark-order-ready" 
                                                    data-order-id="{{ $order->id }}">
                                                Mark All Ready
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <!-- Order Notes -->
                                @if($order->notes)
                                    <div class="mt-3 p-3 bg-yellow-900 border border-yellow-600 rounded-lg">
                                        <p class="text-yellow-200 text-sm">
                                            <strong>Note:</strong> {{ $order->notes }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        @else
            <!-- No Orders State -->
            <div class="flex flex-col items-center justify-center py-12">
                <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.172V5L8 4z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-300 mb-2">No Active Orders</h2>
                <p class="text-gray-400">All orders are completed or no new orders received.</p>
            </div>
        @endif
    </div>
</div>

<!-- Order Complete Sound -->
<audio id="order-sound" preload="auto">
    <source src="/sounds/notification.mp3" type="audio/mpeg">
</audio>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // Manual refresh button
    $('#refresh-btn').click(function() {
        window.location.reload();
    });
    // setInterval(function() {
    //     window.location.reload();
    // }, 30000); 


   
    let currentOrderCount = 0;
let isReloading = false;

function refreshKitchenData() {
    if (isReloading) return; // Prevent multiple reloads
    
    $.get('{{ route("kitchen.live-data") }}')
        .done(function(data) {
            // const nowOrderCount = Object.keys(data.orders).length;
            const nowOrderCount = Object.values(data.orders).reduce((sum, orderList) => {
    // 'sum' is the accumulated total
    // 'orderList.length' is the number of orders for the current table
    return sum + orderList.length;
     }, 0);

            // alert(nowOrderCount);
            
            // Check if new orders have been added
            if (nowOrderCount > currentOrderCount) {
                const newOrdersCount = nowOrderCount - currentOrderCount;
                
                // Show notification
                showNewOrderNotification(newOrdersCount);
                
                // Set reload flag
                isReloading = true;
                
                // Reload after a short delay to show the notification
                setTimeout(() => {
                    console.log(`${newOrdersCount} new order(s) received! Reloading page...`);
                    location.reload();
                }, 2000);
                
                return;
            }
            
            // Update the stored count
            currentOrderCount = nowOrderCount;
            
            // Update the display
            updateKitchenDisplay(data.orders);
            $('#active-orders-count').text(nowOrderCount);
            $('#last-updated').text(new Date().toLocaleTimeString());
        })
        .fail(function() {
            console.log('Failed to refresh kitchen data');
        });
}


   
function showNewOrderNotification(count) {
    // Create notification element
    const notification = $(`
        <div id="new-order-notification" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.07 2.82l3.64 1.15c.75.24 1.29.92 1.29 1.73v8.89c0 .67-.34 1.3-.91 1.66L9 19.07V2.82z"/>
                </svg>
                <span class="font-semibold">
                    ${count} New Order${count > 1 ? 's' : ''} Received!
                </span>
            </div>
            <div class="text-sm mt-1">Page will reload in 2 seconds...</div>
        </div>
    `);
    
    // Add to page
    $('body').append(notification);
    
    // Animate in
    setTimeout(() => {
        notification.removeClass('translate-x-full');
    }, 100);
    
    // Play notification sound (optional)
    // playNotificationSound();
       document.getElementById('order-sound')?.play();
  }


  
      
// function playNotificationSound() {
//     // Create and play notification sound
//        try {
//         const audio = new Audio('/sounds/notification.mp3'); // Add your notification sound file
//         audio.play().catch(e => console.log('Could not play notification sound'));
//        } catch (e) {
//         console.log('Notification sound not available');
//       }
//    }

function playNotificationSound() {
    try {
        const audio = new Audio('/sounds/notification.mp3');
        
        // Add event listeners for debugging
        audio.addEventListener('loadstart', () => console.log('Audio loading started'));
        audio.addEventListener('canplay', () => console.log('Audio can start playing'));
        audio.addEventListener('error', (e) => {
            console.error('Audio error:', e);
            console.error('Audio error details:', audio.error);
        });
        
        // Set volume (optional)
        audio.volume = 0.5;
        
        // Attempt to play
        const playPromise = audio.play();
        
        if (playPromise !== undefined) {
            playPromise
                .then(() => {
                    console.log('Audio played successfully');
                })
                .catch(error => {
                    console.error('Play failed:', error.name, error.message);
                    
                    // Handle specific errors
                    if (error.name === 'NotAllowedError') {
                        console.log('Audio blocked by browser - user interaction required');
                        showAudioPermissionAlert();
                    } else if (error.name === 'NotSupportedError') {
                        console.log('Audio format not supported');
                    }
                });
        }
        
    } catch (e) {
        console.error('Error creating audio:', e);
    }
}



// Initialize when page loads
$(document).ready(function() {
    // Set initial order count
    currentOrderCount = parseInt($('#active-orders-count').text()) || 0;
    
    // Start the refresh interval
    setInterval(refreshKitchenData, 3000); // Check every 3 seconds for faster response
});
    
    // Auto-refresh every 3 seconds
    // setInterval(function() {
    //     refreshKitchenData();
    // }, 3000);
    
    // function refreshKitchenData() {
    //     $.get('{{ route("kitchen.live-data") }}')
    //         .done(function(data) {
    //             updateKitchenDisplay(data.orders);
    //             $('#active-orders-count').text(Object.keys(data.orders).length);
    //             $('#last-updated').text(new Date().toLocaleTimeString());
    //         })
    //         .fail(function() {
    //             console.log('Failed to refresh kitchen data');
    //         });
    // }    
    
    function updateKitchenDisplay(orders) {
        // This would update the display with new order data
        // For simplicity, we'll just reload the page when there are changes
        // In production, you'd want to update individual elements
    }
    
    // Update item status
    $('.update-item-status').click(function() {
        const button = $(this);
        const itemId = button.data('item-id');
        const status = button.data('status');
        
        button.prop('disabled', true).text('Updating...');
        
        $.post(`/kitchen/items/${itemId}/status`, {
            status: status
        })
        .done(function(response) {
            if (response.success) {
                // Update button based on new status
                const itemContainer = button.closest('.order-item');
                
                if (status === 'preparing') {
                    button.replaceWith(`
                        <button class="px-3 py-1 bg-green-600 hover:bg-green-700 rounded-lg text-sm font-medium transition-colors update-item-status" 
                                data-item-id="${itemId}" data-status="ready">
                            Ready
                        </button>
                        <span class="px-3 py-1 bg-orange-600 rounded-lg text-sm font-medium">
                            Cooking
                        </span>
                    `);
                } else if (status === 'ready') {
                    button.siblings().remove();
                    button.replaceWith(`
                        <span class="px-3 py-1 bg-green-600 rounded-lg text-sm font-medium">
                            ✓ Ready
                        </span>
                    `);
                }
                
                // Check if order is complete
                if (response.order_status_updated) {
                    const orderCard = itemContainer.closest('.order-card');
                    orderCard.find('.mt-4.pt-4').html(`
                        <div class="bg-green-600 text-white px-4 py-2 rounded-lg text-center font-bold">
                            🍽️ Order Complete - Ready to Serve!
                        </div>
                    `);

                    setTimeout(() => {
                        // winsdow.location.reload();
                        // alert('Order is complete! Refreshing page...');
                        window.location.reload();
                    }, 2000);
                    
                    // Play notification sound
                    document.getElementById('order-sound')?.play();
                }
                
                showToast(response.message);
            }
        })
        .fail(function() {
            showToast('Failed to update item status', 'error');
        })
        .always(function() {
            button.prop('disabled', false);
        });
    });
    
    // Mark entire order as ready
    $('.mark-order-ready').click(function() {
        const button = $(this);
        const orderId = button.data('order-id');
        
        if (!confirm('Mark all items in this order as ready?')) {
            return;
        }
        
        button.prop('disabled', true).text('Updating...');
        
        $.post(`/kitchen/orders/${orderId}/ready`)
        .done(function(response) {
            if (response.success) {
                const orderCard = button.closest('.order-card');
                
                // Update all item buttons
                orderCard.find('.update-item-status').each(function() {
                    $(this).siblings().remove();
                    $(this).replaceWith(`
                        <span class="px-3 py-1 bg-green-600 rounded-lg text-sm font-medium">
                            ✓ Ready
                        </span>
                    `);
                });
                
                // Update order status
                button.closest('.mt-4.pt-4').html(`
                    <div class="bg-green-600 text-white px-4 py-2 rounded-lg text-center font-bold">
                        🍽️ Order Complete - Ready to Serve!
                    </div>
                `);

                  setTimeout(() => {
                        // winsdow.location.reload();
                        // alert('Order is complete! Refreshing page...');
                        window.location.reload();
                    }, 2000);  
                
                // Play notification sound
                document.getElementById('order-sound')?.play();
                
                showToast(response.message);

              
            }
        })
        .fail(function() {
            showToast('Failed to mark order as ready', 'error');
        });
    });
    
    function showToast(message, type = 'success') {
        const toast = $(`
            <div class="fixed top-4 right-4 z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} text-white px-6 py-3 rounded-lg shadow-lg">
                ${message}
            </div>
        `);
        
        $('body').append(toast);
        
        setTimeout(() => {
            toast.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
});
</script>
@endpush
