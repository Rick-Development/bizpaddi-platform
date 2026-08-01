<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-brand-secondary via-purple-900 to-brand-primary relative overflow-hidden">
            <!-- Decorative circles -->
            <div class="absolute top-0 left-0 w-96 h-96 bg-brand-primary rounded-full mix-blend-multiply filter blur-3xl opacity-20 -translate-x-1/2 -translate-y-1/2 animate-blob"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-brand-secondary rounded-full mix-blend-multiply filter blur-3xl opacity-20 translate-x-1/2 -translate-y-1/2 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-32 left-20 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

            <div class="w-full sm:max-w-lg mt-6 px-8 py-10 bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg shadow-2xl overflow-hidden sm:rounded-2xl text-center relative z-10 border border-white/20">
                @yield('content')
            </div>
            
            <div class="mt-8 text-white text-sm relative z-10">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </div>
        </div>
        
        <style>
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
        </style>
    </body>
</html>
