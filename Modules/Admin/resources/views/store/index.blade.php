@extends('admin::layouts.app')

@section('title', 'Store Products')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold text-gray-900">Store Products</h1>
        <p class="mt-2 text-sm text-gray-700">Browse and manage products from the external store.</p>
    </div>
</div>

@if($errors->any())
    <div class="rounded-md bg-red-50 p-4 mt-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Error fetching products</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul role="list" class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    @forelse($items as $item)
    <div class="group relative flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm hover:shadow-md transition-shadow duration-200">
        <div class="aspect-h-3 aspect-w-4 bg-gray-200 sm:aspect-none group-hover:opacity-75 sm:h-48">
            @if(isset($item['media']['thumbnail']))
                <img src="{{ $item['media']['thumbnail'] }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover object-center sm:h-full sm:w-full">
            @else
                <div class="flex h-full items-center justify-center bg-gray-100 text-gray-400">
                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif
        </div>
        <div class="flex flex-1 flex-col p-4 space-y-2">
            <h3 class="text-sm font-medium text-gray-900">
                <a href="{{ $item['demo_link'] ?? '#' }}" target="_blank">
                    <span aria-hidden="true" class="absolute inset-0"></span>
                    {{ $item['name'] }}
                </a>
            </h3>
            <p class="text-xs text-gray-500 line-clamp-2">{{ $item['description'] }}</p>
            <div class="flex flex-1 flex-col justify-end">
                <div class="flex items-center justify-between pt-2">
                    <p class="text-sm italic text-gray-500">{{ $item['category'] }}</p>
                    @if(isset($item['price']['regular']))
                        <p class="text-base font-medium text-gray-900">{{ $item['currency'] ?? '$' }} {{ $item['price']['regular'] }}</p>
                    @else
                        <p class="text-base font-medium text-green-600">Free</p>
                    @endif
                </div>
                <div class="mt-2 flex items-center justify-between text-xs text-gray-400">
                    <span>v{{ $item['version'] }}</span>
                    <span>{{ \Carbon\Carbon::parse($item['published_at'])->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
        <p class="mt-1 text-sm text-gray-500">Check your API connection or try again later.</p>
    </div>
    @endforelse
</div>
@endsection
