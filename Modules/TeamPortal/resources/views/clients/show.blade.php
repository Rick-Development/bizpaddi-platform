@extends('teamportal::layouts.app')

@section('title', $client->name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('team.clients.index') }}" class="p-2 rounded-xl text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition-colors">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div class="flex items-center gap-3">
             <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-emerald-600 to-[#10231C] border border-white/10 flex items-center justify-center text-white font-bold text-base shadow-sm shrink-0">
                {{ strtoupper(substr($client->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ $client->name }}</h1>
                <p class="mt-0.5 text-xs sm:text-sm text-gray-400">{{ $client->email }}</p>
            </div>
        </div>
        <div class="ml-auto">
             <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold 
                {{ $client->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-white/5 text-gray-400 border border-white/10' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $client->is_active ? 'bg-emerald-400' : 'bg-gray-400' }} mr-1.5"></span>
                {{ $client->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content: Projects & Invoices -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Projects Section -->
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Projects</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                        {{ $client->projects->count() }}
                    </span>
                </div>
                @if($client->projects->isEmpty())
                    <div class="p-8 text-center text-gray-400 text-xs sm:text-sm italic">No projects assigned yet.</div>
                @else
                    <ul role="list" class="divide-y divide-white/5">
                        @foreach($client->projects as $project)
                        <li class="px-6 py-4 hover:bg-white/[0.03] transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0 pr-4">
                                    <p class="text-sm font-bold text-white truncate hover:text-emerald-400 transition-colors">{{ $project->name }}</p>
                                    <div class="flex items-center mt-1 text-xs text-gray-400">
                                        <p>Tasks: {{ $project->tasks->where('status', 'completed')->count() }}/{{ $project->tasks->count() }}</p>
                                        <span class="mx-2 text-gray-600">&bull;</span>
                                        <p>Due: {{ $project->due_date ? $project->due_date->format('M d, Y') : 'N/A' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        {{ $project->status === 'completed' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 
                                           ($project->status === 'active' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/30' : 'bg-white/5 text-gray-300 border border-white/10') }}">
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
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/10 overflow-hidden">
                 <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Invoices</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/5 text-gray-300 border border-white/10">
                        {{ $client->invoices->count() }}
                    </span>
                </div>
                @if($client->invoices->isEmpty())
                    <div class="p-8 text-center text-gray-400 text-xs sm:text-sm italic">No invoices generated yet.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/5 text-left text-xs sm:text-sm">
                            <thead class="bg-white/5">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Invoice #</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($client->invoices as $invoice)
                                <tr class="hover:bg-white/[0.03] transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-white">{{ $invoice->invoice_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400">{{ $invoice->issue_date ? $invoice->issue_date->format('M d, Y') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-white">{{ $invoice->currency ? $invoice->currency->symbol : '$' }}{{ number_format($invoice->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            {{ $invoice->status === 'paid' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 
                                               ($invoice->status === 'sent' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/30' : 
                                               ($invoice->status === 'overdue' ? 'bg-red-500/10 text-red-400 border border-red-500/30' : 'bg-white/5 text-gray-300 border border-white/10')) }}">
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
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/10 p-6">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Contact Information</h3>
                <dl class="space-y-4 text-xs sm:text-sm">
                    <div>
                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Email</dt>
                        <dd class="mt-0.5 text-white font-medium break-all">{{ $client->email }}</dd>
                    </div>
                    @if($client->phone)
                    <div>
                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Phone</dt>
                        <dd class="mt-0.5 text-white font-medium">{{ $client->phone }}</dd>
                    </div>
                    @endif
                    @if($client->company_name)
                    <div>
                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Company</dt>
                        <dd class="mt-0.5 text-white font-medium">{{ $client->company_name }}</dd>
                    </div>
                    @endif
                    @if($client->address)
                    <div>
                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Address</dt>
                        <dd class="mt-0.5 text-gray-300 leading-relaxed">
                            {{ $client->address }}<br>
                            {{ $client->city }}, {{ $client->state }} {{ $client->zip }}<br>
                            {{ $client->country }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Recent Tickets -->
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/10 p-6">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Recent Tickets</h3>
                @if($client->supportTickets->isEmpty())
                    <p class="text-xs text-gray-400 italic">No support tickets.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($client->supportTickets->take(3) as $ticket)
                        <li class="p-3 rounded-2xl bg-[#08110e]/60 border border-white/10">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-white line-clamp-1">#{{ $ticket->id }} - {{ $ticket->subject }}</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">{{ $ticket->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0
                                    {{ $ticket->status === 'open' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-white/5 text-gray-400 border border-white/10' }}">
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
</div>
@endsection
