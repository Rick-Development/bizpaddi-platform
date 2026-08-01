@extends('admin::layouts.app')

@section('title', 'Categories')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Blog Categories</h1>
            <p class="mt-1 text-sm text-gray-600">Organize your blog posts with categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-brand-primary text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-red-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Category
        </a>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posts</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <code class="px-2 py-1 bg-gray-100 rounded text-xs">{{ $category->slug }}</code>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                            {{ Str::limit($category->description, 50) ?: '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $category->posts_count }} {{ Str::plural('post', $category->posts_count) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-brand-primary hover:text-red-900">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This will fail if there are posts in this category.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                            No categories found. <a href="{{ route('admin.categories.create') }}" class="text-brand-primary hover:text-red-900 font-medium">Add one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile/Tablet Card View --}}
    <div class="lg:hidden space-y-4">
        @forelse($categories as $category)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-900">{{ $category->name }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <code class="px-2 py-1 bg-gray-100 rounded">{{ $category->slug }}</code>
                    </p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $category->posts_count }}
                </span>
            </div>
            
            @if($category->description)
            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($category->description, 80) }}</p>
            @endif

            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.categories.edit', $category) }}" 
                   class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure? This will fail if there are posts in this category.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No categories</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
            <div class="mt-6">
                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Category
                </a>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
