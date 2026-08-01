@extends('clientportal::layouts.app')

@section('title', 'Support Tickets')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Support Tickets</h1>
        <p class="mt-2 text-sm text-gray-600">Get help with your projects and account</p>
    </div>
    <a href="{{ route('client.tickets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all shadow-brand-primary/30 hover:-translate-y-0.5">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Ticket
    </a>
</div>

@if($tickets->isEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
    <div class="mx-auto h-16 w-16 rounded-full bg-green-50 flex items-center justify-center mb-4">
        <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-900">No support tickets</h3>
    <p class="mt-2 text-sm text-gray-500">Need help? Create a new support ticket to get started.</p>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($tickets as $ticket)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500">
                                <span class="font-bold text-sm">#{{ $ticket->id }}</span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 line-clamp-1">{{ $ticket->subject }}</div>
                                <div class="text-xs text-gray-500">Created {{ $ticket->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                         <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : 
                               ($ticket->status === 'resolved' ? 'bg-gray-100 text-gray-800' : 
                               ($ticket->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                         <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $ticket->priority === 'high' || $ticket->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                               ($ticket->priority === 'medium' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $ticket->updated_at->diffForHumans() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('client.tickets.show', $ticket->id) }}" class="text-brand-primary hover:text-brand-primary/80">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-6">
    {{ $tickets->links() }}
</div>
@endif
@endsection
