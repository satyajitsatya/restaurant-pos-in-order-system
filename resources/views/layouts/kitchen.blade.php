<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kitchen') - Restaurant POS</title>
    
    <!-- Tailwind CSS CDN -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
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
    
    <style>
        /* Dark theme overrides for kitchen */
        body {
            background-color: #111827;
            color: #f9fafb;
        }
        
        /* Auto-scroll for new orders */
        .order-card {
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Pulsing animation for urgent items */
        .urgent {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.02);
            }
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
    @yield('content')
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
