@extends('clientportal::layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Track Orders</h1>
        <p class="mt-2 text-sm text-gray-600">Monitor the status of your service requests</p>
    </div>
    <a href="{{ route('client.services.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all">
        Browse Services
    </a>
</div>

@if($orders->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="mx-auto h-16 w-16 rounded-full bg-blue-50 flex items-center justify-center mb-4">
        <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">No active orders</h3>
    <p class="mt-2 text-sm text-gray-500">You haven't placed any service orders yet.</p>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500">
                                <span class="font-bold text-lg text-brand-primary">#{{ $order->id }}</span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $order->service->name }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($order->details['requirements'] ?? '', 40) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                         <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                               ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                               ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('client.orders.show', $order->id) }}" class="text-brand-primary hover:text-brand-primary/80">View Details</a>
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
