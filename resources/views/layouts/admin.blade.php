<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Restaurant POS</title>
    
    <!-- Tailwind CSS CDN -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom styles (same as your original) -->
    <style>
        .btn-primary {
            @apply bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
        }
        
        .btn-secondary {
            @apply bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200;
        }
        
        .card {
            @apply bg-white rounded-lg shadow-sm border border-gray-200;
        }
        
        .form-input {
            @apply w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent;
        }
        
        .veg-indicator {
            width: 16px;
            height: 16px;
            border: 2px solid #22c55e;
            border-radius: 2px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .veg-indicator::after {
            content: '';
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
        }

        .non-veg-indicator {
            width: 16px;
            height: 16px;
            border: 2px solid #ef4444;
            border-radius: 2px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .non-veg-indicator::after {
            content: '';
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ONLY MOBILE CHANGES - Simple sidebar toggle */
        @media (max-width: 768px) {
            .mobile-sidebar {
                width: 240px; /* Smaller width on mobile */
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                position: fixed;
                z-index: 1000;
            }
            .mobile-sidebar.show {
                transform: translateX(0);
            }
            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }
            .mobile-overlay.show {
                display: block;
            }
            .main-content {
                margin-left: 0 !important;
            }
        }
    </style>
    
    {{-- <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fef2f2',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                        },
                        orange: {
                            500: '#f97316',
                            600: '#ea580c',
                        }
                    }
                }
            }
        }
    </script> --}}
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="mobile-overlay" onclick="closeMobileSidebar()"></div>

    <!-- Rest of your admin layout content - EXACTLY THE SAME -->
    <div class="min-h-screen flex">
        <!-- Sidebar - Only added mobile classes -->
        <div id="sidebar" class="w-64 bg-white shadow-lg mobile-sidebar md:translate-x-0 md:static md:z-auto">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 bg-orange-500">
                    <h1 class="text-xl font-bold text-white">Restaurant POS</h1>
                </div>
                
                <!-- Navigation - EXACTLY THE SAME -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-orange-50 text-orange-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 002-2h6l2 2h6a2 2 0 012 2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.orders.*') ? 'bg-orange-50 text-orange-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Orders
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.categories.*') ? 'bg-orange-50 text-orange-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Categories
                    </a>
                    
                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 {{ request()->routeIs('admin.products.*') ? 'bg-orange-50 text-orange-700' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                        </svg>
                        Menu Items
                    </a>
                    
                    <a href="{{ route('kitchen.index') }}" 
                       class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.172V5L8 4z"></path>
                        </svg>
                        Kitchen View
                    </a>
                </nav>
                
                <!-- User Info - EXACTLY THE SAME -->
                <div class="p-4 border-t">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content - Only added mobile class -->
        <div class="flex-1 flex flex-col overflow-hidden main-content">
            <!-- Top Header - Only added mobile menu button -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <!-- Mobile Menu Button (3 dots) - ONLY NEW ADDITION -->
                    <button onclick="toggleMobileSidebar()" class="md:hidden mr-4 text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </button>
                    
                    <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Logout - EXACTLY THE SAME -->
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            
            <!-- Page Content - EXACTLY THE SAME -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Toast Container - EXACTLY THE SAME -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Base Scripts - Your original + simple mobile functions -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Your original toast function - EXACTLY THE SAME
        function showToast(message, type = 'success') {
            const toastId = 'toast-' + Date.now();
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            
            const toast = `
                <div id="${toastId}" class="${bgColor} text-white px-4 py-2 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300">
                    ${message}
                </div>
            `;
            
            $('#toast-container').append(toast);
            
            setTimeout(() => {
                $(`#${toastId}`).removeClass('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                $(`#${toastId}`).addClass('translate-x-full');
                setTimeout(() => $(`#${toastId}`).remove(), 300);
            }, 3000);
        }

        // ONLY 2 SIMPLE MOBILE FUNCTIONS ADDED
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            if (sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        // Close sidebar when clicking nav links on mobile
        document.querySelectorAll('#sidebar nav a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    closeMobileSidebar();
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
