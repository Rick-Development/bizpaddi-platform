@extends('clientportal::layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('client.orders.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="mt-1 text-sm text-gray-600">
                Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}
            </p>
        </div>
        <div class="ml-auto">
             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                   ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                   ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content: Details & Feedback -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Status Tracker -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Order Status</h3>
            <div class="relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-between">
                    <div>
                        <span class="bg-white h-8 w-8 rounded-full border-2 border-brand-primary flex items-center justify-center" aria-hidden="true">
                            <span class="h-2.5 w-2.5 bg-brand-primary rounded-full"></span>
                        </span>
                        <span class="mt-2 block text-xs font-medium text-brand-primary text-center">Placed</span>
                    </div>
                    <div>
                        <span class="bg-white h-8 w-8 rounded-full border-2 {{ in_array($order->status, ['reviewed', 'quoted', 'invoiced', 'contracted', 'completed']) ? 'border-brand-primary' : 'border-gray-300' }} flex items-center justify-center" aria-hidden="true">
                            <span class="h-2.5 w-2.5 {{ in_array($order->status, ['reviewed', 'quoted', 'invoiced', 'contracted', 'completed']) ? 'bg-brand-primary' : 'bg-transparent' }} rounded-full"></span>
                        </span>
                        <span class="mt-2 block text-xs font-medium text-gray-500 text-center">Reviewed</span>
                    </div>
                     <div>
                        <span class="bg-white h-8 w-8 rounded-full border-2 {{ in_array($order->status, ['quoted', 'invoiced', 'contracted', 'completed']) ? 'border-brand-primary' : 'border-gray-300' }} flex items-center justify-center" aria-hidden="true">
                            <span class="h-2.5 w-2.5 {{ in_array($order->status, ['quoted', 'invoiced', 'contracted', 'completed']) ? 'bg-brand-primary' : 'bg-transparent' }} rounded-full"></span>
                        </span>
                        <span class="mt-2 block text-xs font-medium text-gray-500 text-center">Quoted</span>
                    </div>
                    <div>
                        <span class="bg-white h-8 w-8 rounded-full border-2 {{ in_array($order->status, ['completed']) ? 'border-brand-primary' : 'border-gray-300' }} flex items-center justify-center" aria-hidden="true">
                            <span class="h-2.5 w-2.5 {{ in_array($order->status, ['completed']) ? 'bg-brand-primary' : 'bg-transparent' }} rounded-full"></span>
                        </span>
                        <span class="mt-2 block text-xs font-medium text-gray-500 text-center">Complete</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Request Details</h3>
            
            <div class="space-y-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Service</h4>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->service->name }}</p>
                </div>
                
                <div>
                     <h4 class="text-sm font-medium text-gray-500">Requirements</h4>
                     <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $order->details['requirements'] ?? 'No specific requirements provided.' }}</p>
                </div>

                @if($order->feature_selections)
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Selected Features</h4>
                    <ul class="mt-2 text-sm text-gray-900 list-disc list-inside">
                        @foreach(Modules\Accounting\Models\FeaturePrice::findMany($order->feature_selections) as $feature)
                        <li>{{ $feature->feature_name }}</li>
                        @endforeach
                    </ul>
                     @if(!empty($order->details['instructions']))
                        <p class="mt-2 text-sm text-gray-500 italic">Instructions: {{ $order->details['instructions'] }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
        
        <!-- Admin Feedback -->
        @if($order->admin_feedback)
        <div class="bg-blue-50 rounded-2xl border border-blue-100 p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 w-full">
                    <h3 class="text-sm font-medium text-blue-800">Note from Admin</h3>
                    <div class="mt-2 text-sm text-blue-700 whitespace-pre-wrap">{{ $order->admin_feedback }}</div>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- Right Sidebar: Next Steps / Documents -->
    <div class="lg:col-span-1 space-y-6">
        @if($order->quote)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quote Available</h3>
            <p class="text-sm text-gray-500 mb-4">A quote has been prepared for your request.</p>
            <a href="{{ route('client.quotes.show', $order->quote->id) }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors"> <!-- Link to View Quote -->
                <div class="flex items-center">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">View Quote</p>
                        <p class="text-xs text-gray-500">{{ $order->quote->quote_number }}</p>
                    </div>
                </div>
                 <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        @endif

        @if($order->contract)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Contract Available</h3>
             <a href="{{ route('client.contracts.show', $order->contract->id) }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <svg class="h-8 w-8 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Download Agreement</p>
                        <p class="text-xs text-gray-500">{{ $order->contract->contract_number }}</p>
                    </div>
                </div>
                 <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        @endif

        @if($order->invoice)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Generated</h3>
            <a href="{{ route('client.invoices.show', $order->invoice->id) }}" class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors mb-4">
                <div class="flex items-center">
                    <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">View Invoice</p>
                        <p class="text-xs text-gray-500">{{ $order->invoice->invoice_number }}</p>
                    </div>
                </div>
                 <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>

            @if($order->invoice->status !== 'paid')
            <a href="{{ route('client.invoices.pay', $order->invoice->id) }}" class="block w-full py-3 px-4 bg-brand-primary hover:bg-brand-primary/90 text-white text-center font-bold rounded-xl transition-all shadow-lg shadow-brand-primary/20">
                Pay Invoice
            </a>
            @endif
        </div>
        @endif
        
        <div class="bg-brand-secondary rounded-2xl shadow-lg p-6 text-white">
            <h3 class="text-lg font-bold mb-2">Need to discuss?</h3>
            <p class="text-gray-200 text-sm mb-4">You can always create a support ticket referencing this order ID.</p>
            <a href="{{ route('client.tickets.create') }}" class="block w-full py-2 px-4 bg-white/10 hover:bg-white/20 text-center font-medium rounded-lg transition-colors border border-white/20">
                Contact Support
            </a>
        </div>
    </div>
</div>
@endsection
