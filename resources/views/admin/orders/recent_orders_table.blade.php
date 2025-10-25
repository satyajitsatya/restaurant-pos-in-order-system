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
            No orders found for {{ \Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}
        </td>
    </tr>
@endforelse
