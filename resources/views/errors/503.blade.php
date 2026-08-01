@extends('errors.layout')

@section('title', __('Service Unavailable'))

@section('content')
    <div class="mb-6">
        <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-12 h-12 text-yellow-600 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
        <h1 class="text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-orange-600 mb-2">
            503
        </h1>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">
            {{ __('Under Maintenance') }}
        </h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-sm mx-auto">
            {{ __($exception->getMessage() ?: 'We are currently performing scheduled maintenance. We will be back shortly.') }}
        </p>
    </div>
    
    <div class="space-y-4">
        <button onclick="window.location.reload()" class="block w-full px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            {{ __('Check Status') }}
        </button>
    </div>
    
    <style>
        .animate-spin-slow {
            animation: spin 3s linear infinite;
        }
    </style>
@endsection
