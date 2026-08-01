<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Client Portal</h1>
            <p class="text-gray-600">Reset your password</p>
        </div>

        <!-- Forgot Password Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
            <div class="mb-4 text-sm text-gray-600">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('client.password.email') }}" class="space-y-6">
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

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 bg-brand-primary text-white rounded-xl font-medium hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary shadow-lg shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                    Email Password Reset Link
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Remember it? <a href="{{ route('client.login') }}" class="text-brand-primary hover:underline font-medium">Log in</a></p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center text-sm text-gray-600">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
