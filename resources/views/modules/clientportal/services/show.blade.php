@extends('clientportal::layouts.app')

@section('title', $service->name)

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-x-4 mb-4">
        <a href="{{ route('client.services.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <nav class="flex" aria-label="Breadcrumb">
            <ol role="list" class="flex items-center space-x-2">
                <li><a href="{{ route('client.services.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Catalog</a></li>
                <li class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z"/></svg>
                    <span class="ml-2 text-sm font-bold text-gray-900">{{ $service->name }}</span>
                </li>
            </ol>
        </nav>
    </div>
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">{{ $service->name }}</h1>
            @if($service->category)
                <span class="inline-flex items-center px-3 py-1 mt-2 rounded-full text-sm font-bold bg-blue-50 text-blue-700">
                    {{ $service->category->name }}
                </span>
            @endif
        </div>
        <div class="flex flex-col items-end">
            <div class="text-3xl font-black text-brand-primary">{{ $currency->symbol }}{{ number_format($service->base_price, 2) }}</div>
            <div class="text-sm font-medium text-gray-500">Starting Price / {{ ucfirst($service->unit) }}</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-10">
        <!-- Service Description -->
        <section class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 md:p-10 overflow-hidden">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="p-2 bg-blue-50 rounded-xl"><svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span>
                Description
            </h2>
            <div class="prose prose-blue max-w-none text-gray-600 leading-relaxed text-lg">
                {!! $service->description !!}
            </div>
        </section>

        <!-- Dynamic CTA for larger screens -->
        <div class="hidden md:block bg-gradient-to-br from-gray-900 to-brand-primary rounded-3xl p-10 text-white shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 h-64 w-64 bg-white/10 rounded-full blur-3xl transition-all group-hover:scale-110"></div>
            <div class="relative z-10">
                <h3 class="text-3xl font-bold mb-4">Ready to get started?</h3>
                <p class="text-white/80 mb-8 max-w-md text-lg">Submit your project details today and our team will get back to you with a formal proposal within 24 hours.</p>
                <a href="{{ route('client.services.configure', $service->id) }}" class="inline-flex items-center px-8 py-4 bg-white text-brand-primary font-black rounded-2xl hover:bg-opacity-90 transition-all hover:scale-105 shadow-xl">
                    Order This Service
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Sidebar / Quick Info -->
    <div class="lg:col-span-1 space-y-8">
        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 p-8 sticky top-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Service Summary</h3>
            
            <dl class="space-y-6">
                <div>
                    <dt class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Base Price</dt>
                    <dd class="text-2xl font-black text-gray-900">{{ $currency->symbol }}{{ number_format($service->base_price, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Billing Unit</dt>
                    <dd class="text-lg font-bold text-gray-900">Per {{ ucfirst($service->unit) }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Typical Delivery</dt>
                    <dd class="text-lg font-bold text-gray-900">Starts in 2-3 business days</dd>
                </div>
            </dl>

            <div class="mt-8 pt-8 border-t border-gray-100">
                <a href="{{ route('client.services.configure', $service->id) }}" class="flex items-center justify-center w-full py-4 px-6 bg-brand-primary text-white font-black rounded-2xl hover:bg-brand-primary/90 transition-all shadow-lg shadow-brand-primary/20 hover:-translate-y-1">
                    Order Service
                </a>
                <p class="mt-4 text-center text-xs text-gray-400 font-medium italic">
                    You won't be charged yet. We'll review your request first.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
