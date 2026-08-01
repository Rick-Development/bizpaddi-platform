@extends('errors.layout')

@section('title', __('Page Not Found'))

@section('content')
    <div class="mb-6">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
            <svg class="w-12 h-12 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-secondary mb-2">
            404
        </h1>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">
            {{ __('Page Not Found') }}
        </h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-sm mx-auto">
            {{ __('Oops! It seems you have ventured into uncharted territory. The page you are looking for does not exist.') }}
        </p>
    </div>
    
    <div class="space-y-4">
        <a href="{{ url('/') }}" class="block w-full px-6 py-3 bg-gradient-to-r from-brand-primary to-red-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            {{ __('Return Home') }}
        </a>
        <button onclick="history.back()" class="block w-full px-6 py-3 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
            {{ __('Go Back') }}
        </button>
    </div>
@endsection
