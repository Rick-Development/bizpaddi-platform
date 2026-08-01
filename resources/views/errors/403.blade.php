@extends('errors.layout')

@section('title', __('Forbidden'))

@section('content')
    <div class="mb-6">
        <div class="w-24 h-24 bg-brand-secondary/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-12 h-12 text-brand-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <h1 class="text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-brand-secondary to-brand-primary mb-2">
            403
        </h1>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">
            {{ __('Access Denied') }}
        </h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-sm mx-auto">
            {{ __($exception->getMessage() ?: 'Sorry, you do not have permission to access this page.') }}
        </p>
    </div>
    
    <div class="space-y-4">
        <a href="{{ url('/') }}" class="block w-full px-6 py-3 bg-gradient-to-r from-brand-secondary to-brand-primary text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            {{ __('Return Home') }}
        </a>
        <button onclick="history.back()" class="block w-full px-6 py-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
            {{ __('Go Back') }}
        </button>
    </div>
@endsection
