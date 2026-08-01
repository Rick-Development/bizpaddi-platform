<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Client Portal</h1>
            <p class="text-gray-600">Sign in to access your account</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <form method="POST" action="{{ route('client.login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Remember me
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 bg-brand-primary text-white rounded-xl font-medium hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary shadow-lg shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Don't have an account? <a href="{{ route('client.register') }}" class="text-brand-primary hover:underline font-medium">Create one</a></p>
                <p class="mt-2 text-sm"><a href="{{ route('client.password.request') }}" class="text-gray-500 hover:text-gray-900">Forgot your password?</a></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Need help? Contact your account manager</p>
        </div>
    </div>
</body>
</html>
