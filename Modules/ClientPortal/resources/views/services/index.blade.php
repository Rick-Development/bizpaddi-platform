@extends('clientportal::layouts.app')

@section('title', 'Service Catalog')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Service Catalog</h1>
        <p class="mt-2 text-sm text-gray-600">Browse our services and find the perfect solution for your needs</p>
    </div>
</div>

@if($categories->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="mx-auto h-16 w-16 rounded-full bg-blue-50 flex items-center justify-center mb-4">
        <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">No services available</h3>
    <p class="mt-2 text-sm text-gray-500">Please check back later.</p>
</div>
@else
<div class="space-y-12">
    @foreach($categories as $category)
        @if($category->services->count() > 0)
        <div>
            <div class="flex items-center gap-x-3 mb-6">
                <span class="h-8 w-1 rounded-full" style="background-color: {{ $category->color ?? '#3B82F6' }}"></span>
                <h2 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($category->services as $service)
                <div class="group flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                    <div class="p-6 flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-brand-primary transition-colors">{{ $service->name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{ $service->unit ?? 'Service' }}
                            </span>
                        </div>
                        
                        <p class="text-gray-500 text-sm line-clamp-3 mb-6">{{ strip_tags($service->description) }}</p>
                        
                        <div class="space-y-4">
                            <div class="flex items-baseline text-gray-900">
                                <span class="text-3xl font-bold tracking-tight">{{ $service->currency->symbol ?? '$' }}{{ number_format($service->base_price, 2) }}</span>
                                <span class="ml-1 text-sm font-medium text-gray-500">starting price</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 pt-0 mt-auto">
                        <a href="{{ route('client.services.show', $service->id) }}" class="block w-full py-3 px-4 bg-gray-50 hover:bg-brand-primary text-gray-900 hover:text-white text-center font-medium rounded-xl transition-all duration-200">
                            View Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach
</div>
@endif
@endsection
