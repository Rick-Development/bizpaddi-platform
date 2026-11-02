<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounting - Bizpaddi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="min-h-screen bg-gradient-to-br from-brand-secondary via-purple-900 to-brand-primary py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl p-8">
            <h1 class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-brand-primary to-brand-secondary mb-8">
                Accounting Dashboard
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Assets</h3>
                    <p class="text-3xl font-bold text-green-600">$0</p>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Liabilities</h3>
                    <p class="text-3xl font-bold text-red-600">$0</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Revenue</h3>
                    <p class="text-3xl font-bold text-green-600">$0</p>
                </div>

                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Expenses</h3>
                    <p class="text-3xl font-bold text-orange-600">$0</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="#" class="bg-brand-primary hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        Record Transaction
                    </a>
                    <a href="#" class="bg-brand-secondary hover:bg-blue-900 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        View Balance Sheet
                    </a>
                    <a href="#" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        Financial Reports
                    </a>
                </div>
            </div>

            <div class="mt-8 text-center text-gray-600">
                <p class="text-sm">Accounting Module - Chart of accounts and transactions ready. CFO analytics coming soon.</p>
                <p class="text-xs mt-2"><a href="/" class="text-brand-primary hover:underline">← Back to Home</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
