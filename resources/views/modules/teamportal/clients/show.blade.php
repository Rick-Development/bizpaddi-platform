@extends('teamportal::layouts.app')

@section('title', $client->name)

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('team.clients.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div class="flex items-center gap-3">
             <div class="h-10 w-10 rounded-full bg-brand-primary flex items-center justify-center text-white font-bold text-sm shadow-md">
                {{ strtoupper(substr($client->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $client->name }}</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $client->email }}</p>
            </div>
        </div>
        <div class="ml-auto">
             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                {{ $client->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                {{ $client->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content: Projects & Invoices -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Projects Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Projects</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ $client->projects->count() }}
                </span>
            </div>
            @if($client->projects->isEmpty())
                <div class="p-8 text-center text-gray-500 text-sm">No projects assigned yet.</div>
            @else
                <ul role="list" class="divide-y divide-gray-100">
                    @foreach($client->projects as $project)
                    <li class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0 pr-4">
                                <p class="text-sm font-medium text-brand-primary truncate">{{ $project->name }}</p>
                                <div class="flex items-center mt-1">
                                    <p class="text-xs text-gray-500">
                                        Tasks: {{ $project->tasks->where('status', 'completed')->count() }}/{{ $project->tasks->count() }}
                                    </p>
                                    <span class="mx-2 text-gray-300">&bull;</span>
                                    <p class="text-xs text-gray-500">Due: {{ $project->due_date ? $project->due_date->format('M d') : 'N/A' }}</p>
                                </div>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $project->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($project->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Invoices Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
             <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Invoices</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ $client->invoices->count() }}
                </span>
            </div>
            @if($client->invoices->isEmpty())
                <div class="p-8 text-center text-gray-500 text-sm">No invoices generated yet.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($client->invoices as $invoice)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invoice->issue_date ? $invoice->issue_date->format('M d, Y') : 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->currency ? $invoice->currency->symbol : '$' }}{{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                           ($invoice->status === 'sent' ? 'bg-blue-100 text-blue-800' : 
                                           ($invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Sidebar: Info & Tickets -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Contact Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900 break-all">{{ $client->email }}</dd>
                </div>
                @if($client->phone)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->phone }}</dd>
                </div>
                @endif
                @if($client->company_name)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Company</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $client->company_name }}</dd>
                </div>
                @endif
                @if($client->address)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $client->address }}<br>
                        {{ $client->city }}, {{ $client->state }} {{ $client->zip }}<br>
                        {{ $client->country }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Recent Tickets -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Tickets</h3>
            @if($client->supportTickets->isEmpty())
                <p class="text-sm text-gray-500">No support tickets.</p>
            @else
                <ul class="space-y-4">
                    @foreach($client->supportTickets->take(3) as $ticket)
                    <li>
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 line-clamp-1">#{{ $ticket->id }} - {{ $ticket->subject }}</p>
                                <p class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
