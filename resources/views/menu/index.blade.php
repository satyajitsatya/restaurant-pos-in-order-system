@extends('layouts.app')

@section('title', 'Our Menu' . ($tableInfo ? ' - Table ' . $tableInfo->table_number : ''))

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Enhanced Mobile Header -->
    <header class="sticky top-0 bg-white shadow-lg z-20 lg:hidden border-b">
        <div class="px-4 py-4 flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-xl font-bold text-gray-900">Our Menu</h1>
                @if($tableInfo)
                    <div class="flex items-center mt-1">
                        <svg class="w-4 h-4 text-orange-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <p class="text-sm text-gray-600 font-medium">Table {{ $tableInfo->table_number }}</p>
                    </div>
                @endif
            </div>
            
            <div class="flex items-center space-x-3">
                <!-- Track Order Button -->
                <a href="{{ route('menu.track-order') }}" 
                   class="bg-blue-500 text-white px-4 py-2.5 rounded-full text-sm font-semibold hover:bg-blue-600 transition-all duration-200 transform hover:scale-105 shadow-md flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Track
                </a>
                
                <!-- Cart Button -->
                <button id="cart-btn" class="relative bg-gradient-to-r from-orange-500 to-orange-600 text-white px-4 py-2.5 rounded-full flex items-center hover:from-orange-600 hover:to-orange-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9.5M7 13l1.5 9.5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"/>
                    </svg>
                    <span class="mr-2 font-semibold">Cart</span>
                    <span id="cart-count" class="bg-white text-orange-600 rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold shadow-inner">0</span>
                </button>
            </div>
        </div>
        
        <!-- Enhanced Search Bar -->
        <div class="px-4 pb-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" id="search-input" placeholder="Search delicious dishes..." 
                       class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-full focus:ring-2 focus:ring-orange-500 focus:border-transparent bg-white shadow-sm transition-all duration-200">
            </div>
        </div>
    </header>

    <div class="lg:flex">
        <!-- Enhanced Desktop Sidebar -->
        <aside class="hidden lg:block lg:w-72 lg:fixed lg:h-full bg-white shadow-lg border-r">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Our Menu</h2>
                    @if($tableInfo)
                        <div class="flex items-center p-3 bg-orange-50 rounded-lg border border-orange-200">
                            <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="text-orange-800 font-semibold">Table {{ $tableInfo->table_number }}</span>
                        </div>
                    @endif
                </div>
                
                <!-- Enhanced Search -->
                <div class="relative mb-6">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" id="desktop-search" placeholder="Search dishes..." 
                           class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                </div>
                
                <!-- Enhanced Filters -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                        </svg>
                        Filters
                    </h3>
                    <label class="flex items-center space-x-3 cursor-pointer hover:bg-white p-2 rounded transition-colors">
                        <input type="checkbox" id="veg-filter" class="rounded text-green-500 focus:ring-green-500 w-4 h-4">
                        <span class="text-sm font-medium text-gray-700 flex items-center">
                            <div class="veg-indicator mr-2"></div>
                            Vegetarian Only
                        </span>
                    </label>
                </div>
                
                <!-- Enhanced Categories -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Categories
                    </h3>
                    <div class="space-y-2">
                        <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-orange-50 text-gray-700 category-btn active transition-all duration-200 flex items-center justify-between group" 
                                data-category="all">
                            <span class="font-medium">All Items</span>
                            <div class="w-2 h-2 bg-orange-500 rounded-full opacity-100 group-hover:scale-110 transition-transform"></div>
                        </button>
                        @foreach($categories as $category)
                            <button class="w-full text-left px-4 py-3 rounded-lg hover:bg-orange-50 text-gray-700 category-btn transition-all duration-200 flex items-center justify-between group" 
                                    data-category="{{ $category->id }}">
                                <span class="font-medium">{{ $category->name }}</span>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $category->products_count }}</span>
                                    <div class="w-2 h-2 bg-transparent rounded-full group-hover:bg-orange-500 transition-all duration-200"></div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>

        <!-- Enhanced Main Content -->
        <main class="lg:ml-72 p-4 lg:p-6">
            <!-- Enhanced Mobile Category Pills -->
            <div class="flex overflow-x-auto pb-4 mb-6 lg:hidden scrollbar-hide space-x-3">
                <button class="flex-shrink-0 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-full font-semibold shadow-lg category-btn active transition-all duration-200 transform hover:scale-105" 
                        data-category="all">
                    All
                </button>
                @foreach($categories as $category)
                    <button class="flex-shrink-0 px-6 py-3 bg-white text-gray-700 rounded-full font-semibold shadow-md hover:shadow-lg category-btn transition-all duration-200 transform hover:scale-105 border border-gray-200" 
                            data-category="{{ $category->id }}">
                        {{ $category->name }}
                        <span class="ml-2 text-xs bg-gray-100 px-2 py-1 rounded-full">{{ $category->products_count }}</span>
                    </button>
                @endforeach
            </div>

            <!-- Enhanced Mobile Filters -->
            <div class="mb-6 lg:hidden">
                <div class="bg-white rounded-lg p-4 shadow-sm border">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" id="mobile-veg-filter" class="rounded text-green-500 focus:ring-green-500 w-5 h-5">
                        <span class="text-sm font-medium text-gray-700 flex items-center">
                            <div class="veg-indicator mr-2"></div>
                            Show Vegetarian Only
                        </span>
                    </label>
                </div>
            </div>

            <!-- Enhanced Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-6" id="products-grid">
                @foreach($products as $product)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 product-card border border-gray-100 overflow-hidden" 
                         data-category="{{ $product->category_id }}" 
                         data-veg="{{ $product->is_veg ? 'true' : 'false' }}"
                         data-name="{{ strtolower($product->name) }}">
                        
                        <!-- Enhanced Product Image -->
                        <div class="relative overflow-hidden">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-40 sm:h-48 object-cover transform hover:scale-110 transition-transform duration-500"
                                     loading="lazy">
                            @else
                                <div class="w-full h-40 sm:h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Enhanced Spice Level Badge -->
                            @if($product->spice_level !== 'mild')
                                <div class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm
                                           @if($product->spice_level === 'medium') bg-yellow-100/90 text-yellow-800 border border-yellow-200
                                           @else bg-red-100/90 text-red-800 border border-red-200 @endif">
                                    @if($product->spice_level === 'medium') 🌶️🌶️ Medium
                                    @else 🌶️🌶️🌶️ Hot @endif
                                </div>
                            @endif

                            <!-- Veg/Non-Veg Badge -->
                            <div class="absolute top-3 left-3">
                                @if($product->is_veg)
                                    <div class="veg-indicator shadow-sm"></div>
                                @else
                                    <div class="non-veg-indicator shadow-sm"></div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Enhanced Product Details -->
                        <div class="p-4 lg:p-5">
                            <!-- Product Name -->
                            <h3 class="font-bold text-gray-900 text-base lg:text-lg mb-2 line-clamp-2 leading-tight">
                                {{ $product->name }}
                            </h3>
                            
                            <!-- Description -->
                            @if($product->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                    {{ $product->description }}
                                </p>
                            @endif
                            
                            <!-- Prep Time -->
                            <div class="flex items-center mb-4 text-xs text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $product->prep_time }} mins prep time
                            </div>
                            
                            <!-- Price and Add Button -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xl lg:text-2xl font-bold text-orange-600">₹{{ number_format($product->price, 0) }}</span>
                                </div>
                                
                                <button class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-4 lg:px-6 py-2.5 lg:py-3 rounded-full font-semibold text-sm lg:text-base add-to-cart transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg" 
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->name }}"
                                        data-product-price="{{ $product->price }}">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Add
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Enhanced Pagination -->
            @if($products->hasPages())
                <div class="mt-12 flex justify-center">
                    <div class="bg-white rounded-lg shadow-sm border p-2">
                        {{ $products->links() }}
                    </div>
                </div>
            @endif
        </main>
    </div>
</div>

<!-- Enhanced Cart Sidebar -->
<div id="cart-sidebar" class="fixed inset-y-0 right-0 w-full sm:w-96 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 z-30">
    <div class="flex flex-col h-full">
        <!-- Enhanced Cart Header -->
        <div class="flex items-center justify-between p-4 lg:p-6 border-b bg-gradient-to-r from-orange-50 to-orange-100">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9.5M7 13l1.5 9.5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-900">Your Order</h2>
            </div>
            <button id="close-cart" class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-white transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Enhanced Cart Items -->
        <div class="flex-1 overflow-y-auto p-4 lg:p-6">
            <div id="cart-items">
                <!-- Cart items will be populated by JavaScript -->
            </div>
        </div>
        
        <!-- Enhanced Cart Footer -->
        <div class="border-t bg-white p-4 lg:p-6">
            <div class="flex justify-between items-center mb-4 p-3 bg-gray-50 rounded-lg">
                <span class="font-semibold text-gray-700">Total Amount:</span>
                <span class="font-bold text-2xl text-orange-600" id="cart-total">₹0</span>
            </div>
            
            <button id="checkout-btn" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white py-4 rounded-xl font-bold text-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105 shadow-lg" disabled>
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2z"/>
                    </svg>
                    Place Order
                </span>
            </button>
        </div>
    </div>
</div>

<!-- Cart Overlay -->
<div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-20 hidden transition-opacity duration-300"></div>

<!-- Enhanced Checkout Modal -->
<div id="checkout-modal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-40 hidden p-4">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl transform scale-95 transition-transform duration-300">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Complete Order</h2>
                <button id="cancel-checkout" class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="checkout-form" class="space-y-4">
                @if($tableInfo)
                    <input type="hidden" name="table_id" value="{{ $tableInfo->id }}">
                    <div class="p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="font-semibold text-orange-800">Table {{ $tableInfo->table_number }}</span>
                        </div>
                    </div>
                @else
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Select Table *</label>
                        <select name="table_id" class="form-input w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                            <option value="">Choose a table</option>
                            @foreach ($table as $table)
                                <option value="{{ $table->id }}">Table {{ $table->table_number }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Your Name *</label>
                    <input type="text" name="customer_name" class="form-input w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Guests *</label>
                    <select name="guest_count" class="form-input w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                        <option value="1">1 Person</option>
                        <option value="2">2 People</option>
                        <option value="3">3 People</option>
                        <option value="4">4 People</option>
                        <option value="5">5+ People</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Method *</label>
                    <select name="payment_method" class="form-input w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent" required>
                        <option value="counter">Pay at Counter</option>
                        <option value="card">Card Payment</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Special Instructions</label>
                    <textarea name="notes" rows="3" class="form-input w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none" placeholder="Any special requests..."></textarea>
                </div>
                
                <div class="flex space-x-4 pt-4">
                    <button type="button" id="cancel-checkout-btn" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 rounded-lg font-semibold transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white py-3 rounded-lg font-semibold transition-all duration-200 transform hover:scale-105">
                        Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Enhanced scrollbar hide */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Enhanced animations */
@keyframes bounce-in {
    0% { transform: scale(0.3); opacity: 0; }
    50% { transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); opacity: 1; }
}

.animate-bounce-in {
    animation: bounce-in 0.5s ease-out;
}

/* Enhanced card hover effects */
.product-card:hover .veg-indicator,
.product-card:hover .non-veg-indicator {
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Enhanced button states */
.add-to-cart.added {
    background: linear-gradient(135deg, #10b981, #059669) !important;
    transform: scale(0.95);
}

.add-to-cart.adding {
    background: linear-gradient(135deg, #3b82f6, #2563eb) !important;
    transform: scale(1.05);
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .product-card {
        margin-bottom: 1rem;
    }
    
    #cart-sidebar {
        border-radius: 1rem 0 0 1rem;
    }
}

/* Enhanced modal animations */
#checkout-modal.show .bg-white {
    transform: scale(1);
}

/* Custom checkbox and radio styles */
input[type="checkbox"]:checked,
input[type="radio"]:checked {
    background-color: #f97316;
    border-color: #f97316;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    let cart = [];
    
    // Enhanced add to cart with better animations
    $('.add-to-cart').click(function() {
        const button = $(this);
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        const productPrice = parseFloat($(this).data('product-price'));
        
        // Prevent double clicks
        if (button.hasClass('adding')) return;
        
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

        // Enhanced button animation
        const originalContent = button.html();
        
        button.addClass('adding')
              .html(`
                  <svg class="animate-spin w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Adding...
              `)
              .prop('disabled', true);
        
        // Enhanced cart button animation
        $('#cart-btn').addClass('animate-bounce');
        $('#cart-count').addClass('animate-bounce-in');
        
        setTimeout(() => {
            button.removeClass('adding')
                  .addClass('added')
                  .html(`
                      <span class="flex items-center">
                          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                          </svg>
                          Added!
                      </span>
                  `)
                  .prop('disabled', false);
            
            $('#cart-btn').removeClass('animate-bounce');
            $('#cart-count').removeClass('animate-bounce-in');
            
            // setTimeout(() => {
            //     button.removeClass('added').html(originalContent);
            // }, 2000);
        }, 1000);
    });
    
    // Enhanced cart update function
    function updateCart() {
        const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
        const cartTotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
        
        $('#cart-count').text(cartCount);
        $('#cart-total').text('₹' + cartTotal.toLocaleString('en-IN'));
        
        // Enhanced button states
        $('#checkout-btn').prop('disabled', cartCount === 0).toggleClass('opacity-50', cartCount === 0);
        
        updateCartItems();
    }
    
    // Enhanced cart items display
    function updateCartItems() {
        const cartItemsContainer = $('#cart-items');
        cartItemsContainer.empty();
        
        if (cart.length === 0) {
            cartItemsContainer.html(`
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 9.5M7 13l1.5 9.5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"/>
                    </svg>
                    <p class="text-gray-500 text-lg font-medium">Your cart is empty</p>
                    <p class="text-gray-400 text-sm">Add some delicious items to get started!</p>
                </div>
            `);
            return;
        }
        
        cart.forEach((item, index) => {
            const itemHtml = `
                <div class="flex items-center justify-between py-4 border-b border-gray-100 hover:bg-gray-50 rounded-lg px-2 transition-colors duration-200">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900 mb-1">${item.name}</h4>
                        <p class="text-orange-600 font-bold text-lg">₹${item.price.toLocaleString('en-IN')}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-lg font-bold decrease-qty transition-colors duration-200" data-index="${index}">−</button>
                        <span class="w-8 text-center font-bold text-lg text-gray-900">${item.quantity}</span>
                        <button class="w-8 h-8 rounded-full bg-orange-500 hover:bg-orange-600 text-white flex items-center justify-center text-lg font-bold increase-qty transition-colors duration-200" data-index="${index}">+</button>
                    </div>
                </div>
            `;
            cartItemsContainer.append(itemHtml);
        });
    }
    
    // Enhanced quantity controls
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
    
    // Enhanced cart sidebar controls
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
    
    // Enhanced checkout process
    $('#checkout-btn').click(function() {
        $('#checkout-modal').removeClass('hidden').addClass('show');
    });
    
    $('#cancel-checkout, #cancel-checkout-btn').click(function() {
        $('#checkout-modal').addClass('hidden').removeClass('show');
    });
    
    // Enhanced order submission
    $('#checkout-form').submit(function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.html(`
            <svg class="animate-spin w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `).prop('disabled', true);
        
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
                showToast('Order placed successfully! Order #' + response.order_id, 'success');
                
                // Reset and close
                cart = [];
                updateCart();
                $('#checkout-modal').addClass('hidden').removeClass('show');
                $('#cart-sidebar').addClass('translate-x-full');
                $('#cart-overlay').addClass('hidden');
                $('body').removeClass('overflow-hidden');
                
                // Enhanced confirmation modal
                setTimeout(() => {
                    const confirmationModal = `
                        <div id="order-confirmation" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50">
                            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 text-center shadow-2xl transform animate-bounce-in">
                                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">Order Confirmed!</h3>
                                <p class="text-gray-600 mb-2">Order #${response.order_id}</p>
                                <p class="text-lg font-semibold text-orange-600 mb-6">Total: ₹${response.total_amount.toLocaleString('en-IN')}</p>
                                <div class="space-y-3">
                                    <a href="/track-order/${response.order_id}" 
                                       class="block w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white py-3 px-6 rounded-xl font-semibold transition-all duration-200 transform hover:scale-105">
                                        Track Your Order
                                    </a>
                                    <button onclick="window.location.reload()" 
                                           class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 py-3 px-6 rounded-xl font-semibold transition-colors duration-200">
                                        Continue Browsing
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    $('body').append(confirmationModal);
                }, 300);
            }
        })
        .fail(function() {
            showToast('Failed to place order. Please try again.', 'error');
        })
        .always(function() {
            submitBtn.html(originalText).prop('disabled', false);
        });
    });
    
    // Enhanced category filtering
    $('.category-btn').click(function() {
        const category = $(this).data('category');
        
        $('.category-btn').removeClass('active');
        $(this).addClass('active');
        
        // Enhanced mobile category styling
        if ($(window).width() < 1024) {
            $('.category-btn').removeClass('bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg')
                              .addClass('bg-white text-gray-700 shadow-md border border-gray-200');
            $(this).removeClass('bg-white text-gray-700 shadow-md border border-gray-200')
                   .addClass('bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg');
        }
        
        filterProducts();
    });
    
    // Enhanced vegetarian filter
    $('#veg-filter, #mobile-veg-filter').change(function() {
        const isChecked = $(this).is(':checked');
        $('#veg-filter, #mobile-veg-filter').prop('checked', isChecked);
        filterProducts();
    });
    
    // Enhanced search functionality
    $('#search-input, #desktop-search').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('#search-input, #desktop-search').val(searchTerm);
        filterProducts();
    });
    
    // Enhanced filter function with animations
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
            
            if (activeCategory !== 'all' && cardCategory != activeCategory) showCard = false;
            if (vegOnly && !isVeg) showCard = false;
            if (searchTerm && !productName.includes(searchTerm)) showCard = false;
            
            if (showCard) {
                $card.fadeIn(300);
            } else {
                $card.fadeOut(300);
            }
        });
    }
    
    // Initialize
    updateCart();
});
</script>
@endpush
