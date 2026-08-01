@extends('errors.layout')

@section('title', __('Server Error'))

@section('content')
    <div class="mb-6">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-12 h-12 text-brand-primary animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        <h1 class="text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary mb-2">
            500
        </h1>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">
            {{ __('Server Error') }}
        </h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-sm mx-auto">
            {{ __('Whoops! Something went wrong on our end. We are working to fix it immediately.') }}
        </p>
    </div>
    
    <div class="space-y-4">
        <button onclick="window.location.reload()" class="block w-full px-6 py-3 bg-gradient-to-r from-brand-primary to-red-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            {{ __('Try Again') }}
        </button>
        <a href="{{ url('/') }}" class="block w-full px-6 py-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
            {{ __('Return Home') }}
        </a>
    </div>
@endsection
