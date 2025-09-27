@extends('layouts.app')

{{-- @section('title', 'Our Menu') --}}
@section('title', 'Our Menu' . ($tableInfo ? ' - Table ' . $tableInfo->table_number ? $tableInfo->table_number :'Not selected'  : ''))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Mobile Header -->
    <header class="sticky top-0 bg-white shadow-sm z-20 lg:hidden">
        <div class="px-4 py-3 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Our Menu</h1>
                @if($tableInfo)
                    <p class="text-sm text-gray-600">Table {{ $tableInfo->table_number }}</p>
                @endif
            </div>
            
         <div class="flex items-center space-x-2">
            <!-- Track Order Button -->
            <a href="{{ route('menu.track-order') }}" 
               class="bg-blue-500 text-white px-3 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors">
                Track Order
            </a>
            
            <!-- Cart Button -->
            <button id="cart-btn" class="relative bg-orange-500 text-white px-4 py-2 rounded-lg flex items-center hover:bg-orange-600 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9.5M7 13l1.5 9.5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"></path>
                </svg>
                <span class="mr-2">Cart</span>
                <span id="cart-count" class="bg-white text-orange-500 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold">0</span>
            </button>
        </div>
        </div>
        
        <!-- Search Bar -->
        <div class="px-4 pb-3">
            <input type="text" id="search-input" placeholder="Search dishes..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
        </div>
    </header>

    <div class="lg:flex">
        <!-- Desktop Categories Sidebar -->
        <aside class="hidden lg:block lg:w-64 lg:fixed lg:h-full bg-white shadow-sm">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Our Menu</h2>
                @if($tableInfo)
                    <p class="text-sm text-gray-600 mb-6">Table {{ $tableInfo->table_number }}</p>
                @endif
                
                <!-- Search -->
                <input type="text" id="desktop-search" placeholder="Search dishes..." 
                       class="w-full px-3 py-2 mb-6 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                
                <!-- Filters -->
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Filters</h3>
                    <label class="flex items-center space-x-2 mb-2">
                        <input type="checkbox" id="veg-filter" class="rounded text-orange-500 focus:ring-orange-500">
                        <span class="text-sm">Vegetarian Only</span>
                    </label>
                </div>
                
                <!-- Categories -->
                <h3 class="font-semibold text-gray-900 mb-4">Categories</h3>
                <div class="space-y-1">
                    <button class="w-full text-left px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700 category-btn active" 
                            data-category="all">
                        All Items
                    </button>
                    @foreach($categories as $category)
                        <button class="w-full text-left px-3 py-2 rounded-lg hover:bg-gray-100 text-gray-700 category-btn" 
                                data-category="{{ $category->id }}">
                            {{ $category->name }}
                            <span class="text-xs text-gray-500">({{ $category->products_count }})</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="lg:ml-64 p-4">
            <!-- Mobile Category Pills -->
            <div class="flex overflow-x-auto pb-4 mb-6 lg:hidden scrollbar-hide">
                <button class="flex-shrink-0 px-4 py-2 bg-orange-500 text-white rounded-full mr-3 text-sm font-medium shadow-sm category-btn active" 
                        data-category="all">
                    All
                </button>
                @foreach($categories as $category)
                    <button class="flex-shrink-0 px-4 py-2 bg-white text-gray-700 rounded-full mr-3 text-sm font-medium shadow-sm hover:bg-gray-100 category-btn" 
                            data-category="{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <!-- Mobile Filters -->
            <div class="mb-6 lg:hidden">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" id="mobile-veg-filter" class="rounded text-orange-500 focus:ring-orange-500">
                    <span class="text-sm">Vegetarian Only</span>
                </label>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="products-grid">
                @foreach($products as $product)
                    <div class="card overflow-hidden product-card hover:shadow-lg transition-shadow duration-200" 
                         data-category="{{ $product->category_id }}" 
                         data-veg="{{ $product->is_veg ? 'true' : 'false' }}"
                         data-name="{{ strtolower($product->name) }}">
                        
                        <!-- Product Image -->
                        <div class="relative">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-32 sm:h-40 object-cover"
                                     loading="lazy">
                            @else
                                <div class="w-full h-32 sm:h-40 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Spice Level Badge -->
                            @if($product->spice_level !== 'mild')
                                <div class="absolute top-2 right-2 px-2 py-1 rounded-full text-xs font-medium
                                           @if($product->spice_level === 'medium') bg-yellow-100 text-yellow-800
                                           @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($product->spice_level) }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Details -->
                        <div class="p-4">
                            <!-- Name and Veg Indicator -->
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-semibold text-gray-900 text-sm sm:text-base line-clamp-2 flex-1">
                                    {{ $product->name }}
                                </h3>
                                <div class="ml-2 mt-1">
                                    @if($product->is_veg)
                                        <div class="veg-indicator"></div>
                                    @else
                                        <div class="non-veg-indicator"></div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Description -->
                            @if($product->description)
                                <p class="text-gray-600 text-xs sm:text-sm mb-3 line-clamp-2">
                                    {{ $product->description }}
                                </p>
                            @endif
                            
                            <!-- Price and Add Button -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-bold text-orange-500">₹{{ number_format($product->price, 0) }}</span>
                                    <div class="text-xs text-gray-500">{{ $product->prep_time }} mins</div>
                                </div>
                                
                                <button class="btn-primary text-sm add-to-cart" 
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->name }}"
                                        data-product-price="{{ $product->price }}">
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </main>
    </div>
</div>

<!-- Cart Sidebar -->
<div id="cart-sidebar" class="fixed inset-y-0 right-0 w-80 bg-white shadow-xl transform translate-x-full transition-transform duration-300 z-30">
    <div class="flex flex-col h-full">
        <!-- Cart Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <h2 class="text-lg font-semibold">Your Order</h2>
            <button id="close-cart" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4">
            <div id="cart-items">
                <!-- Cart items will be populated by JavaScript -->
            </div>
        </div>
        
        <!-- Cart Footer -->
        <div class="border-t p-4">
            <div class="flex justify-between items-center mb-4">
                <span class="font-semibold">Total:</span>
                <span class="font-bold text-lg text-orange-500" id="cart-total">₹0</span>
            </div>
            
            <button id="checkout-btn" class="w-full btn-primary disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                Place Order
            </button>
        </div>
    </div>
</div>

<!-- Cart Overlay -->
<div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden"></div>

<!-- Checkout Modal -->
<div id="checkout-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <h2 class="text-xl font-semibold mb-4">Complete Your Order</h2>
        
        <form id="checkout-form">
            @if($tableInfo)
                <input type="hidden" name="table_id" value="{{ $tableInfo->id }}">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Table</label>
                    <input type="text" value="Table {{ $tableInfo->table_number }}" class="form-input" readonly>
                </div>
            @else
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Table</label>
                    <select name="table_id" class="form-input" required>
                        <option value="">Choose a table</option>
                        @foreach ($table as $table)
                            <option value="{{ $table->id }}">Table {{ $table->table_number }} </option>
                            
                        @endforeach
                        <!-- Table options will be loaded here -->
                    </select>
                </div>
            @endif
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                <input type="text" name="customer_name" class="form-input" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Number of Guests</label>
                <select name="guest_count" class="form-input" required>
                    <option value="1">1 Person</option>
                    <option value="2">2 People</option>
                    <option value="3">3 People</option>
                    <option value="4">4 People</option>
                    <option value="5">5+ People</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="payment_method" class="form-input" required>
                    <option value="counter">Pay at Counter</option>
                    <option value="card">Card Payment</option>
                    <option value="cash">Cash</option>
                </select>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Special Instructions (Optional)</label>
                <textarea name="notes" rows="3" class="form-input" placeholder="Any special requests..."></textarea>
            </div>
            
            <div class="flex space-x-3">
                <button type="button" id="cancel-checkout" class="flex-1 btn-secondary">
                    Cancel
                </button>
                <button type="submit" class="flex-1 btn-primary">
                    Place Order
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let cart = [];
    
    // Add to cart functionality
    $('.add-to-cart').click(function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        const productPrice = parseFloat($(this).data('product-price'));
        
        // Check if item already in cart
        const existingItem = cart.find(item => item.id === productId);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: productPrice,
                quantity: 1
            });
        }
        
        updateCart();
        showToast(`${productName} added to cart!`);
    });
    
    // Update cart display
    function updateCart() {
        const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
        const cartTotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        
        $('#cart-count').text(cartCount);
        $('#cart-total').text('₹' + cartTotal.toLocaleString('en-IN'));
        
        // Enable/disable checkout button
        $('#checkout-btn').prop('disabled', cartCount === 0);
        
        // Update cart items display
        updateCartItems();
    }
    
    // Update cart items list
    function updateCartItems() {
        const cartItemsContainer = $('#cart-items');
        cartItemsContainer.empty();
        
        if (cart.length === 0) {
            cartItemsContainer.html('<p class="text-gray-500 text-center py-8">Your cart is empty</p>');
            return;
        }
        
        cart.forEach((item, index) => {
            const itemHtml = `
                <div class="flex items-center justify-between py-3 border-b">
                    <div class="flex-1">
                        <h4 class="font-medium text-sm">${item.name}</h4>
                        <p class="text-orange-500 font-semibold">₹${item.price.toLocaleString('en-IN')}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-sm decrease-qty" data-index="${index}">-</button>
                        <span class="w-8 text-center font-medium">${item.quantity}</span>
                        <button class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center text-sm increase-qty" data-index="${index}">+</button>
                    </div>
                </div>
            `;
            cartItemsContainer.append(itemHtml);
        });
    }
    
    // Cart quantity controls
    $(document).on('click', '.increase-qty', function() {
        const index = $(this).data('index');
        cart[index].quantity += 1;
        updateCart();
    });
    
    $(document).on('click', '.decrease-qty', function() {
        const index = $(this).data('index');
        if (cart[index].quantity > 1) {
            cart[index].quantity -= 1;
        } else {
            cart.splice(index, 1);
        }
        updateCart();
    });
    
    // Cart sidebar controls
    $('#cart-btn').click(function() {
        $('#cart-sidebar').removeClass('translate-x-full');
        $('#cart-overlay').removeClass('hidden');
        $('body').addClass('overflow-hidden');
    });
    
    $('#close-cart, #cart-overlay').click(function() {
        $('#cart-sidebar').addClass('translate-x-full');
        $('#cart-overlay').addClass('hidden');
        $('body').removeClass('overflow-hidden');
    });
    
    // Checkout process
    $('#checkout-btn').click(function() {
        $('#checkout-modal').removeClass('hidden');
    });
    
    $('#cancel-checkout').click(function() {
        $('#checkout-modal').addClass('hidden');
    });
    
    // Submit order
    $('#checkout-form').submit(function(e) {
        e.preventDefault();
        
        const formData = {
            table_id: $('select[name="table_id"]').val() || $('input[name="table_id"]').val(),
            customer_name: $('input[name="customer_name"]').val(),
            guest_count: $('select[name="guest_count"]').val(),
            payment_method: $('select[name="payment_method"]').val(),
            notes: $('textarea[name="notes"]').val(),
            cart_items: cart.map(item => ({
                product_id: item.id,
                quantity: item.quantity
            }))
        };
        
        $.post('{{ route("menu.place-order") }}', formData)
        .done(function(response) {
         if (response.success) {
            showToast('Order placed successfully! Order #' + response.order_id);

            // Reset cart and close modals
            cart = [];
            updateCart();
            $('#checkout-modal').addClass('hidden');
            $('#cart-sidebar').addClass('translate-x-full');
            $('#cart-overlay').addClass('hidden');
            $('body').removeClass('overflow-hidden');

            // Show order confirmation with tracking link
            setTimeout(() => {
                const confirmationModal = `
                    <div id="order-confirmation" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Order Placed Successfully!</h3>
                            <p class="text-gray-600 mb-4">
                                Order #${response.order_id}<br>
                                Total: ₹${response.total_amount.toLocaleString('en-IN')}
                            </p>
                            <div class="space-y-3">
                                <a href="/track-order/${response.order_id}" 
                                   class="block w-full bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                    Track Your Order
                                </a>
                               <button onclick="$('#order-confirmation').remove()" 
                                       class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-lg font-medium transition-colors">
                                   Continue Browsing
                               </button>
                           </div>
                       </div>
                   </div>
               </div>
           `;
           $('body').append(confirmationModal);
       }, 500);
    }
})
            .fail(function() {
                showToast('Failed to place order. Please try again.', 'error');
            });
    });
    
    // Category filtering
    $('.category-btn').click(function() {
        const category = $(this).data('category');
        
        // Update active state
        $('.category-btn').removeClass('active');
        $(this).addClass('active');
        
        // For mobile, update button styles
        if ($(window).width() < 1024) {
            $('.category-btn').removeClass('bg-orange-500 text-white').addClass('bg-white text-gray-700');
            $(this).removeClass('bg-white text-gray-700').addClass('bg-orange-500 text-white');
        }
        
        filterProducts();
    });
    
    // Vegetarian filter
    $('#veg-filter, #mobile-veg-filter').change(function() {
        // Sync both checkboxes
        const isChecked = $(this).is(':checked');
        $('#veg-filter, #mobile-veg-filter').prop('checked', isChecked);
        filterProducts();
    });
    
    // Search functionality
    $('#search-input, #desktop-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('#search-input, #desktop-search').val(searchTerm);
        filterProducts();
    });
    
    // Filter products function
    function filterProducts() {
        const activeCategory = $('.category-btn.active').data('category');
        const vegOnly = $('#veg-filter').is(':checked');
        const searchTerm = $('#search-input').val().toLowerCase();
        
        $('.product-card').each(function() {
            const $card = $(this);
            const cardCategory = $card.data('category');
            const isVeg = $card.data('veg');
            const productName = $card.data('name');
            
            let showCard = true;
            
            // Category filter
            if (activeCategory !== 'all' && cardCategory != activeCategory) {
                showCard = false;
            }
            
            // Veg filter
            if (vegOnly && !isVeg) {
                showCard = false;
            }
            
            // Search filter
            if (searchTerm && !productName.includes(searchTerm)) {
                showCard = false;
            }
            
            if (showCard) {
                $card.show();
            } else {
                $card.hide();
            }
        });
    }
    
    // Initialize cart
    updateCart();
});
</script>
@endpush
