@extends('teamportal::layouts.app')

@section('title', 'Order #' . $order->id)

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
            <p class="mt-2 text-sm text-gray-600">Placed by {{ $order->client->name }} on {{ $order->created_at->format('M d, Y') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('team.bdm.service-orders.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Back to List</a>
        </div>
    </div>
@endsection

@section('content')

@if(session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content: Details & Actions -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Request Details</h3>
            
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Service</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->service->name }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Requirements & Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $order->details['requirements'] ?? 'No requirements specified.' }}</dd>
                </div>

                @if($order->feature_selections)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Selected Features</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <ul class="list-disc list-inside">
                            @foreach(Modules\Accounting\Models\FeaturePrice::findMany($order->feature_selections) as $feature)
                            <li>{{ $feature->feature_name }}</li>
                            @endforeach
                        </ul>
                         @if(!empty($order->details['instructions']))
                            <p class="mt-2 text-sm text-gray-500 italic">Instructions: {{ $order->details['instructions'] }}</p>
                        @endif
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Workflow Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Process Order</h3>

            <!-- Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Quote Conversion -->
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h4 class="text-sm font-bold text-gray-900 mb-2">Create Quote</h4>
                    @if($order->quote)
                        <div class="text-sm text-green-600 mb-2 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Linked: #{{ $order->quote->quote_number }}
                        </div>
                        <a href="{{ route('team.bdm.quotes.show', $order->quote->id) }}" class="text-brand-primary text-sm hover:underline">View Quote</a>
                    @else
                        <p class="text-xs text-gray-500 mb-3">Convert this request into a formal quote.</p>
                        <form action="{{ route('team.bdm.service-orders.convert-quote', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-brand-secondary hover:bg-brand-secondary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-secondary">
                                Generate Quote
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Invoice Conversion -->
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h4 class="text-sm font-bold text-gray-900 mb-2">Create Invoice</h4>
                    @if($order->invoice)
                         <div class="text-sm text-green-600 mb-2 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Linked: #{{ $order->invoice->invoice_number }}
                        </div>
                        <a href="{{ route('team.bdm.invoices.show', $order->invoice->id) }}" class="text-brand-primary text-sm hover:underline">View Invoice</a>
                    @else
                        <p class="text-xs text-gray-500 mb-3">Convert this request directly to an invoice.</p>
                         <form action="{{ route('team.bdm.service-orders.convert-invoice', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-brand-secondary hover:bg-brand-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Generate Invoice
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Contract (Placeholder) -->
                <!-- Contract Conversion -->
                <div class="p-4 border border-gray-200 rounded-lg">
                    <h4 class="text-sm font-bold text-gray-900 mb-2">Create Contract</h4>
                    @if($order->contract)
                         <div class="text-sm text-green-600 mb-2 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Linked: {{ $order->contract->contract_number }}
                        </div>
                        <a href="{{ route('team.bdm.contracts.show', $order->contract->id) }}" class="text-brand-primary text-sm hover:underline">View Contract</a>
                    @else
                        <p class="text-xs text-gray-500 mb-3">Draft a customized contract for this order.</p>
                         <form action="{{ route('team.bdm.service-orders.convert-contract', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-brand-secondary hover:bg-brand-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-secondary">
                                Generate Contract
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <hr class="border-gray-200 mb-6">

            <!-- Manual Updates / Feedback -->
            <form action="{{ route('team.bdm.service-orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Order Status</label>
                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-brand-primary focus:border-brand-primary sm:text-sm rounded-md">
                            @foreach(['pending', 'reviewed', 'quoted', 'invoiced', 'contracted', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="admin_feedback" class="block text-sm font-medium text-gray-700">Feedback for Client</label>
                        <p class="text-xs text-gray-500 mb-1">This will be visible to the client in their portal.</p>
                        <textarea id="admin_feedback" name="admin_feedback" rows="3" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md">{{ old('admin_feedback', $order->admin_feedback) }}</textarea>
                    </div>

                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700">Internal Notes</label>
                        <p class="text-xs text-gray-500 mb-1">Only visible to admins.</p>
                        <textarea id="admin_notes" name="admin_notes" rows="3" class="shadow-sm focus:ring-gray-500 focus:border-gray-500 block w-full sm:text-sm border-gray-300 rounded-md bg-gray-50">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                        Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Sidebar: Client Info -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Client Overview</h3>
            
            <div class="flex items-center mb-4">
                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                    {{ strtoupper(substr($order->client->name, 0, 2)) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $order->client->name }}</p>
                    <p class="text-xs text-gray-500">{{ $order->client->email }}</p>
                </div>
            </div>

            <dl class="space-y-3">
                <div>
                     <dt class="text-xs font-medium text-gray-500 uppercase">Company</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->client->company_name ?? 'N/A' }}</dd>
                </div>
                <div>
                     <dt class="text-xs font-medium text-gray-500 uppercase">Phone</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->client->phone ?? 'N/A' }}</dd>
                </div>
            </dl>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('team.bdm.clients.show', $order->client->id) }}" class="text-sm text-brand-primary hover:underline">View Client Profile &rarr;</a>
            </div>
        </div>
    </div>
</div>
@endsection
