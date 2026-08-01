@extends('admin::layouts.app')

@section('title', 'Home Features')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('Home Features') }}</h1>
            <p class="mt-1 text-sm text-gray-600">Manage features displayed on homepage</p>
        </div>
        <a href="{{ route('admin.home.features.create') }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-brand-primary text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-red-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('Add Feature') }}
        </a>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Title') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Description') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Icon') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Sort Order') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($features as $feature)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $feature->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($feature->description, 50) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($feature->icon)
                                <i class="{{ $feature->icon }}"></i> <span class="text-xs text-gray-500">{{ $feature->icon }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $feature->sort_order }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                            <a href="{{ route('admin.home.features.edit', $feature->id) }}" class="text-brand-primary hover:text-red-900">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.home.features.destroy', $feature->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                            {{ __('No features found.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile/Tablet Card View --}}
    <div class="lg:hidden space-y-4">
        @forelse($features as $feature)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-start gap-3 mb-3">
                @if($feature->icon)
                    <div class="flex-shrink-0 w-10 h-10 bg-brand-primary/10 rounded-lg flex items-center justify-center">
                        <i class="{{ $feature->icon }} text-brand-primary text-xl"></i>
                    </div>
                @endif
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-900">{{ $feature->title }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $feature->description }}</p>
                </div>
            </div>
            <div class="text-sm text-gray-600 mb-4">
                <span class="font-medium">{{ __('Sort Order') }}:</span> {{ $feature->sort_order }}
            </div>
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.home.features.edit', $feature->id) }}" 
                   class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    {{ __('Edit') }}
                </a>
                <form action="{{ route('admin.home.features.destroy', $feature->id) }}" method="POST" class="flex-1" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-3 py-2 bg-red-50 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <p class="text-sm text-gray-500">{{ __('No features found.') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
