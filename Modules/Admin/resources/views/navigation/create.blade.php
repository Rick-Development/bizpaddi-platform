@extends('admin::layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Create Navigation Item
            </h2>
        </div>
    </div>

    <div class="mt-8">
        <form action="{{ route('admin.navigation.store') }}" method="POST" class="space-y-8 divide-y divide-gray-200">
            @csrf
            
            <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                <div>
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Item Details</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Information about the menu link.</p>
                    </div>

                    <div class="mt-6 sm:mt-5 space-y-6 sm:space-y-5">
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="label" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Label
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input type="text" name="label" id="label" required class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="url" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                URL
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input type="text" name="url" id="url" class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-2 text-sm text-gray-500">e.g., /about or https://example.com</p>
                            </div>
                        </div>

                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="route_name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Route Name (Optional)
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input type="text" name="route_name" id="route_name" class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-2 text-sm text-gray-500">Overrides URL if present. e.g., store.index</p>
                            </div>
                        </div>

                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="order" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Order
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <input type="number" name="order" id="order" value="0" class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="location" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Location
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <select name="location" id="location" class="max-w-lg block w-full focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm sm:max-w-xs sm:text-sm border-gray-300 rounded-md">
                                    <option value="header">Header</option>
                                    <option value="footer">Footer</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
                            <label for="is_active" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">
                                Active Status
                            </label>
                            <div class="mt-1 sm:mt-0 sm:col-span-2">
                                <select name="is_active" id="is_active" class="max-w-lg block w-full focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm sm:max-w-xs sm:text-sm border-gray-300 rounded-md">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="pt-5">
                <div class="flex justify-end">
                    <a href="{{ route('admin.navigation.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancel</a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
