@extends('admin::layouts.app')

@section('title', 'Service Orders')

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Service Orders</h1>
            <p class="mt-2 text-sm text-gray-400">Review and manage client service requests</p>
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

<!-- Filters -->
<div class="mb-6 bg-[#10231C]/60 backdrop-blur-xl rounded-2xl shadow-xl border border-white/10 p-4">
    <form action="{{ route('admin.accounting.service-orders.index') }}" method="GET" class="flex gap-4">
        <div class="flex-1">
            <select name="status" class="block w-full pl-3 pr-10 py-2.5 text-sm bg-[#0a1612] border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                <option value="quoted" {{ request('status') == 'quoted' ? 'selected' : '' }}>Quoted</option>
                <option value="invoiced" {{ request('status') == 'invoiced' ? 'selected' : '' }}>Invoiced</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>
        <button type="submit" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-lg text-white bg-emerald-600 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            Filter
        </button>
    </form>
</div>

@if($orders->isEmpty())
<div class="bg-[#10231C]/60 backdrop-blur-xl rounded-2xl shadow-xl border border-white/10 p-12 text-center text-white">
    <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto text-emerald-400">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
    </div>
    <h3 class="mt-4 text-base font-bold text-white">No service orders found</h3>
    <p class="mt-1 text-xs text-gray-400">Orders placed by your clients through your digital storefront will appear here.</p>
</div>
@else
<div class="bg-[#10231C]/60 backdrop-blur-xl shadow-xl rounded-2xl border border-white/10 overflow-hidden text-white">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-white/5">
            <thead class="bg-white/5">
                <tr>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Order ID</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Client</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Service</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Date</th>
                    <th scope="col" class="relative px-6 py-3.5 text-right text-xs font-bold text-gray-400 uppercase">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($orders as $order)
                <tr class="hover:bg-white/[0.02] transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-emerald-400">
                        <a href="{{ route('admin.accounting.service-orders.show', $order->id) }}" class="hover:underline">
                            #{{ $order->id }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                        <span class="font-bold block">{{ $order->client?->name ?? 'Guest Client' }}</span>
                        @if($order->client?->company_name)
                        <div class="text-xs text-gray-400">{{ $order->client->company_name }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                        {{ $order->service?->name ?? 'Custom Service' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                            {{ $order->status === 'completed' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 
                               ($order->status === 'pending' ? 'bg-amber-500/20 text-amber-300 border border-amber-400/30' : 
                               ($order->status === 'cancelled' ? 'bg-rose-500/20 text-rose-300 border border-rose-500/30' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30')) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold">
                        <a href="{{ route('admin.accounting.service-orders.show', $order->id) }}" class="text-emerald-400 hover:text-emerald-300 transition">View &rarr;</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $orders->links() }}
</div>
@endif
@endsection
