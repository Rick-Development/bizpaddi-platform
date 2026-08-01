<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management - Rick Development LTD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="min-h-screen bg-gradient-to-br from-brand-secondary via-purple-900 to-brand-primary py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-2xl p-8">
            <h1 class="text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-brand-primary to-brand-secondary mb-8">
                Project Management Dashboard
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Active Tasks</h3>
                    <p class="text-3xl font-bold text-brand-primary">0</p>
                    <p class="text-sm text-gray-600 mt-2">In progress</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Team Members</h3>
                    <p class="text-3xl font-bold text-brand-primary">0</p>
                    <p class="text-sm text-gray-600 mt-2">Assigned</p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Completed</h3>
                    <p class="text-3xl font-bold text-green-600">0</p>
                    <p class="text-sm text-gray-600 mt-2">This month</p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="#" class="bg-brand-primary hover:bg-red-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        Create New Task
                    </a>
                    <a href="#" class="bg-brand-secondary hover:bg-blue-900 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        View All Tasks
                    </a>
                    <a href="#" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        Assign Tasks
                    </a>
                    <a href="#" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        Project Reports
                    </a>
                </div>
            </div>

            <div class="mt-8 text-center text-gray-600">
                <p class="text-sm">Project Management - Task assignment and tracking system ready.</p>
                <p class="text-xs mt-2"><a href="/" class="text-brand-primary hover:underline">← Back to Home</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
