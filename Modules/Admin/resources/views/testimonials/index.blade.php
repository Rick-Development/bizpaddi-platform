@extends('admin::layouts.app')

@section('title', 'Testimonials')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Testimonials</h1>
            <p class="mt-1 text-sm text-gray-600">Manage client testimonials</p>
        </div>
        <a href="{{ route('admin.testimonials.create') }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-brand-primary text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-red-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Testimonial
        </a>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($testimonials as $testimonial)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $testimonial->author_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $testimonial->author_position }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-5 w-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($testimonial->is_featured)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Featured</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Not Featured</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-brand-primary hover:text-red-900">Edit</a>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                            No testimonials found. <a href="{{ route('admin.testimonials.create') }}" class="text-brand-primary hover:text-red-900 font-medium">Create one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile/Tablet Card View --}}
    <div class="lg:hidden space-y-4">
        @forelse($testimonials as $testimonial)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-900">{{ $testimonial->author_name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $testimonial->author_position }}</p>
                </div>
                @if($testimonial->is_featured)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Featured</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Not Featured</span>
                @endif
            </div>
            <div class="flex items-center mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="h-5 w-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                @endfor
            </div>
            <p class="text-sm text-gray-600 mb-4 italic">"{{ Str::limit($testimonial->message, 100) }}"</p>
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" 
                   class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    Edit
                </a>
                <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-3 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <p class="text-sm text-gray-500">
                No testimonials found. <a href="{{ route('admin.testimonials.create') }}" class="text-brand-primary hover:text-red-900 font-medium">Create one</a>
            </p>
        </div>
        @endforelse
    </div>
</div>
@endsection
