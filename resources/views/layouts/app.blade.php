<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Restaurant POS')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <style>
        /* Custom Restaurant Styles */
        /* .btn-primary {
            @apply bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200;
        }
        
        .btn-
        .form-input {
            @apply w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent;
        }secondary {
            @apply bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200;
        }
        
        .card {
            @apply bg-white rounded-lg shadow-sm border border-gray-200;
        }
        
         */


             /* Custom Restaurant Styles - Regular CSS */
    .btn-primary {
        background-color: #f97316; /* orange-500 */
        color: white;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: background-color 0.2s ease-in-out;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }
    
    .btn-primary:hover {
        background-color: #ea580c; /* orange-600 */
    }
    
    .btn-primary:disabled {
        background-color: #9ca3af;
        cursor: not-allowed;
    }
    
    .btn-secondary {
        background-color: #e5e7eb; /* gray-200 */
        color: #1f2937; /* gray-800 */
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: background-color 0.2s ease-in-out;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }
    
    .btn-secondary:hover {
        background-color: #d1d5db; /* gray-300 */
    }
    
    .card {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
    }
    
    .form-input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        transition: all 0.2s ease-in-out;
        background-color: white;
    }
    
    .form-input:focus {
        outline: none;
        ring: 2px;
        ring-color: #f97316;
        border-color: transparent;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.5);
    }
    

        .cart-btn {
       
            background-color: #0990f8;
            border-color: #16a34a;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            color: white;
            font-weight: 500;
            transition: background-color 0.2s;
            cursor: pointer;
            border: 1px solid #16a34a;
        }

        /* .place-order {
            background-color: #3b82f6;
            border-color: #2563eb;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            color: white;
            font-weight: 500;
            transition: background-color 0.2s;
            cursor: pointer;
            border: 1px solid #2563eb;
        } */
        /* Veg/Non-veg indicators */
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
        
        /* Hide scrollbar but keep functionality */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    
    <!-- Custom Tailwind Configuration -->
    <script>
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
    </script>
    
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    @yield('navigation')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @yield('footer')
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
        <!-- Toasts will be added here via JavaScript -->
    </div>
    
    @stack('scripts')
    
    <!-- Base JavaScript for AJAX setup -->
    <script>
        // Setup CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Toast notification function
        function showToast(message, type = 'success') {
            const toastId = 'toast-' + Date.now();
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            
            const toast = `
                <div id="${toastId}" class="${bgColor} text-white px-4 py-2 rounded-lg shadow-lg">
                    ${message}
                </div>
            `;
            
            $('#toast-container').append(toast);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                $(`#${toastId}`).fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
        
        // Global error handler for AJAX requests
        $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
            if (jqXHR.status === 422) {
                const errors = jqXHR.responseJSON.errors;
                Object.values(errors).forEach(errorArray => {
                    errorArray.forEach(error => showToast(error, 'error'));
                });
            } else {
                showToast('An error occurred. Please try again.', 'error');
            }
        });
    </script>
</body>
</html>
