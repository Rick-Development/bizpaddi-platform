@extends('admin::layouts.app')

@section('title', 'Home CTAs')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('Home CTAs') }}</h1>
            <p class="mt-1 text-sm text-gray-600">Manage call-to-action sections on homepage</p>
        </div>
        <a href="{{ route('admin.home.ctas.create') }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-brand-primary text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-red-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('Add CTA') }}
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ctas as $cta)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $cta->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($cta->description, 50) }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($cta->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('Active') }}</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('Inactive') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                            <a href="{{ route('admin.home.ctas.edit', $cta->id) }}" class="text-brand-primary hover:text-red-900">{{ __('Edit') }}</a>
                            <form action="{{ route('admin.home.ctas.destroy', $cta->id) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                            {{ __('No CTAs found.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile/Tablet Card View --}}
    <div class="lg:hidden space-y-4">
        @forelse($ctas as $cta)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-gray-900">{{ $cta->title }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $cta->description }}</p>
                </div>
                @if($cta->is_active)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ __('Active') }}</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ __('Inactive') }}</span>
                @endif
            </div>
            <div class="flex gap-2 pt-3 border-t border-gray-100">
                <a href="{{ route('admin.home.ctas.edit', $cta->id) }}" 
                   class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-brand-primary text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    {{ __('Edit') }}
                </a>
                <form action="{{ route('admin.home.ctas.destroy', $cta->id) }}" method="POST" class="flex-1" onsubmit="return confirm('{{ __('Are you sure?') }}')">
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
            <p class="text-sm text-gray-500">{{ __('No CTAs found.') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
