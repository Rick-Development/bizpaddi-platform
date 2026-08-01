@extends('admin::layouts.app')

@section('title', 'Home Stats')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('Home Stats') }}</h1>
            <p class="mt-1 text-sm text-gray-600">Manage statistics displayed on homepage</p>
        </div>
        <a href="{{ route('admin.home.stats.create') }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-brand-primary text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-red-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('Add Stat') }}
        </a>
    </div>

    {{-- Desktop Table View --}}
    <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Value') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Label') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Sort Order') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stats as $stat)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $stat->value }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $stat->label }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $stat->sort_order }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                            <a href="{{ route('admin.home.stats.edit', $stat->id) }}" class="text-brand-primary hover:text-red-900">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.home.stats.destroy', $stat->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                            {{ __('No stats found.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile/Tablet Card View --}}
    <div class="lg:hidden space-y-4">
        @forelse($stats as $stat)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="mb-3">
                <div class="text-2xl font-bold text-brand-primary">{{ $stat->value }}</div>
                <div class="text-sm text-gray-900 font-medium mt-1">{{ $stat->label }}</div>
            </div>
            <div class="text-sm text-gray-600 mb-4">
                <span class="font-medium">{{ __('Sort Order') }}:</span> {{ $stat->sort_order }}
            </div>
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.home.stats.edit', $stat->id) }}" 
                   class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    {{ __('Edit') }}
                </a>
                <form action="{{ route('admin.home.stats.destroy', $stat->id) }}" method="POST" class="flex-1" onsubmit="return confirm('{{ __('Are you sure?') }}')">
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
            <p class="text-sm text-gray-500">{{ __('No stats found.') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
