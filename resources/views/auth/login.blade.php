@extends('layouts.app')

@section('title', 'Login - Restaurant POS')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-100">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo/Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-orange-500 rounded-full flex items-center justify-center mb-6">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 7.172V5L8 4z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">Restaurant POS</h2>
            <p class="mt-2 text-sm text-gray-600">Sign in to your account</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <p class="mb-1">{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Email Address') }}
                    </label>
                    <div class="relative">
                        {{-- <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div> --}}
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email" 
                            autofocus
                            class="form-input w-full pl-10 @error('email') border-red-300 ring-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                            placeholder="Enter your email address">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Password') }}
                    </label>
                    <div class="relative">
                        {{-- <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div> --}}
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            autocomplete="current-password"
                            class="form-input pl-10 @error('password') border-red-300 ring-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                            placeholder="Enter your password">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            {{ old('remember') ? 'checked' : '' }}
                            class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-orange-600 hover:text-orange-500">
                                {{ __('Forgot Password?') }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="w-full btn-primary text-base font-medium">
                        {{ __('Sign In') }}
                    </button>
                </div>
            </form>

            <!-- Demo Credentials -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-blue-800 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Demo Accounts
                </h3>
                <div class="space-y-2 text-xs text-blue-700">
                    <div class="flex justify-between">
                        <span class="font-medium">Admin:</span>
                        <span>admin@restaurant.com / password123</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Kitchen:</span>
                        <span>kitchen@restaurant.com / password123</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Staff:</span>
                        <span>waiter@restaurant.com / password123</span>
                    </div>
                </div>
            </div>

            <!-- Footer Links -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Need help? 
                    <a href="{{ route('menu.index') }}" class="font-medium text-orange-600 hover:text-orange-500">
                        View Menu
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-fill demo credentials
    $('.demo-fill').click(function() {
        const email = $(this).data('email');
        const password = $(this).data('password');
        
        $('#email').val(email);
        $('#password').val(password);
        
        // Add visual feedback
        $('#email, #password').addClass('ring-2 ring-green-400');
        setTimeout(() => {
            $('#email, #password').removeClass('ring-2 ring-green-400');
        }, 1000);
    });
    
    // Form validation feedback
    $('form').submit(function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.html(`
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Signing in...
        `).prop('disabled', true);
    });
});
</script>
@endpush
