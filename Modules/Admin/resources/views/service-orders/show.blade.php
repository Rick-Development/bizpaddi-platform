@extends('admin::layouts.app')

@section('title', 'Order #' . $order->id)

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Order #{{ $order->id }}</h1>
            <p class="mt-2 text-sm text-gray-400">Placed by {{ $order->client?->name ?? 'Guest Client' }} on {{ $order->created_at->format('M d, Y') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.accounting.service-orders.index') }}" class="text-sm font-semibold text-emerald-400 hover:text-emerald-300 transition">&larr; Back to Orders</a>
        </div>
    </div>
@endsection

@section('content')

@if(session('success'))
    <div class="mb-4 bg-emerald-500/20 border-l-4 border-emerald-400 p-4 rounded-r-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-emerald-300 font-semibold">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content: Details & Actions -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Order Details -->
        <div class="bg-[#10231C]/60 backdrop-blur-xl rounded-2xl shadow-xl border border-white/10 p-6 text-white">
            <h3 class="text-lg font-bold text-white mb-4">Request Details</h3>
            
            <dl class="space-y-4">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">Service</dt>
                    <dd class="mt-1 text-sm font-semibold text-emerald-400">{{ $order->service?->name ?? 'Custom Service' }}</dd>
                </div>
                
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">Requirements & Notes</dt>
                    <dd class="mt-1 text-sm text-gray-200 whitespace-pre-wrap bg-[#0a1612] p-4 rounded-xl border border-white/5">{{ $order->details['requirements'] ?? 'No requirements specified.' }}</dd>
                </div>

                @if($order->feature_selections)
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">Selected Features</dt>
                    <dd class="mt-1 text-sm text-gray-200">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach(Modules\Accounting\Models\FeaturePrice::findMany($order->feature_selections) as $feature)
                            <li>{{ $feature->feature_name }}</li>
                            @endforeach
                        </ul>
                        @if(!empty($order->details['instructions']))
                            <p class="mt-2 text-xs text-gray-400 italic">Instructions: {{ $order->details['instructions'] }}</p>
                        @endif
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Workflow Actions -->
        <div class="bg-[#10231C]/60 backdrop-blur-xl rounded-2xl shadow-xl border border-white/10 p-6 text-white">
            <h3 class="text-lg font-bold text-white mb-4">Process Order</h3>

            <!-- Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Quote Conversion -->
                <div class="p-4 border border-white/10 bg-[#0a1612] rounded-xl flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-white mb-1">Create Quote</h4>
                        @if($order->quote)
                            <div class="text-xs text-emerald-400 mb-2 flex items-center font-semibold">
                                <svg class="h-4 w-4 mr-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Linked: #{{ $order->quote->quote_number }}
                            </div>
                            <a href="{{ route('admin.accounting.quotes.show', $order->quote->id) }}" class="text-emerald-400 text-xs hover:underline font-bold">View Quote &rarr;</a>
                        @else
                            <p class="text-xs text-gray-400 mb-3">Convert this request into a formal quote.</p>
                        @endif
                    </div>
                    @if(!$order->quote)
                    <form action="{{ route('admin.accounting.service-orders.convert-quote', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-xs font-bold rounded-lg text-white bg-emerald-600 hover:bg-emerald-500 transition">
                            Generate Quote
                        </button>
                    </form>
                    @endif
                </div>

                <!-- Invoice Conversion -->
                <div class="p-4 border border-white/10 bg-[#0a1612] rounded-xl flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-white mb-1">Create Invoice</h4>
                        @if($order->invoice)
                            <div class="text-xs text-emerald-400 mb-2 flex items-center font-semibold">
                                <svg class="h-4 w-4 mr-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Linked: #{{ $order->invoice->invoice_number }}
                            </div>
                            <a href="{{ route('admin.invoices.show', $order->invoice->id) }}" class="text-emerald-400 text-xs hover:underline font-bold">View Invoice &rarr;</a>
                        @else
                            <p class="text-xs text-gray-400 mb-3">Convert this request directly to an invoice.</p>
                        @endif
                    </div>
                    @if(!$order->invoice)
                    <form action="{{ route('admin.accounting.service-orders.convert-invoice', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-xs font-bold rounded-lg text-white bg-emerald-600 hover:bg-emerald-500 transition">
                            Generate Invoice
                        </button>
                    </form>
                    @endif
                </div>

                <!-- Contract Conversion -->
                <div class="p-4 border border-white/10 bg-[#0a1612] rounded-xl flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-white mb-1">Create Contract</h4>
                        @if($order->contract)
                            <div class="text-xs text-emerald-400 mb-2 flex items-center font-semibold">
                                <svg class="h-4 w-4 mr-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Linked: {{ $order->contract->contract_number }}
                            </div>
                            <a href="{{ route('admin.contracts.show', $order->contract->id) }}" class="text-emerald-400 text-xs hover:underline font-bold">View Contract &rarr;</a>
                        @else
                            <p class="text-xs text-gray-400 mb-3">Draft a customized contract for this order.</p>
                        @endif
                    </div>
                    @if(!$order->contract)
                    <form action="{{ route('admin.accounting.service-orders.convert-contract', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-xs font-bold rounded-lg text-white bg-emerald-600 hover:bg-emerald-500 transition">
                            Generate Contract
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <hr class="border-white/10 mb-6">

            <!-- Manual Updates / Feedback -->
            <form action="{{ route('admin.accounting.service-orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Order Status</label>
                        <select id="status" name="status" class="block w-full pl-3 pr-10 py-2.5 text-sm bg-[#0a1612] border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @foreach(['pending', 'reviewed', 'quoted', 'invoiced', 'contracted', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="admin_feedback" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Feedback for Client</label>
                        <p class="text-[11px] text-gray-400 mb-1">This will be visible to the client in their portal.</p>
                        <textarea id="admin_feedback" name="admin_feedback" rows="3" class="block w-full text-sm bg-[#0a1612] border border-white/10 rounded-xl text-white p-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('admin_feedback', $order->admin_feedback) }}</textarea>
                    </div>

                    <div>
                        <label for="admin_notes" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Internal Notes</label>
                        <p class="text-[11px] text-gray-400 mb-1">Only visible to admins.</p>
                        <textarea id="admin_notes" name="admin_notes" rows="3" class="block w-full text-sm bg-[#0a1612] border border-white/10 rounded-xl text-white p-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-transparent shadow-lg text-xs font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-500 transition">
                        Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Sidebar: Client Info -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-[#10231C]/60 backdrop-blur-xl rounded-2xl shadow-xl border border-white/10 p-6 text-white">
            <h3 class="text-lg font-bold text-white mb-4">Client Overview</h3>
            
            <div class="flex items-center mb-4">
                <div class="h-10 w-10 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center justify-center font-bold text-sm">
                    {{ strtoupper(substr($order->client?->name ?? 'G', 0, 2)) }}
                </div>
                <div class="ml-3 truncate">
                    <p class="text-sm font-bold text-white truncate">{{ $order->client?->name ?? 'Guest Client' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $order->client?->email ?? 'No email provided' }}</p>
                </div>
            </div>

            <dl class="space-y-3 pt-3 border-t border-white/5">
                <div>
                    <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Company</dt>
                    <dd class="mt-1 text-sm font-semibold text-white">{{ $order->client?->company_name ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Phone</dt>
                    <dd class="mt-1 text-sm font-semibold text-white">{{ $order->client?->phone ?? 'N/A' }}</dd>
                </div>
            </dl>
            
            @if($order->client)
            <div class="mt-6 pt-4 border-t border-white/10">
                <a href="{{ route('admin.clients.show', $order->client->id) }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">View Client Profile &rarr;</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
