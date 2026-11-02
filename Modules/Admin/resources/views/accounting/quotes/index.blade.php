@extends('admin::layouts.app')

@section('title', 'Quotes')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Quotes</h1>
            <p class="mt-1 text-sm text-gray-400">Manage and track your quotes</p>
        </div>
        <a href="{{ route('admin.accounting.quotes.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors shadow-sm">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Quote
        </a>
    </div>
</div>

<!-- please set all their buttons text color to either our primary or secondary colors.  all the buttons -->

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-gradient-to-br from-purple-50 to-purple-100 opacity-50 blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
        <div class="relative z-10">
             <div class="flex items-center justify-between mb-4">
                 <div class="h-10 w-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                 </div>
                 <span class="text-xs font-medium text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full">+4.2%</span>
             </div>
             <p class="text-sm font-medium text-gray-400">Total Quotes</p>
            <p class="text-3xl font-bold text-white mt-1">{{ $quotes->total() }}</p>
        </div>
    </div>
</div>

@if($quotes->isEmpty())
<div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-12 text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
    </svg>
    <h3 class="mt-4 text-lg font-semibold text-white">No quotes yet</h3>
    <p class="mt-2 text-sm text-gray-400">Create your first quote to start proposing to clients.</p>
    <div class="mt-6">
        <a href="{{ route('admin.accounting.quotes.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Quote
        </a>
    </div>
</div>
@else
<div class="space-y-4">
    <div class="bg-white/[0.03]/50 rounded-xl p-3 grid grid-cols-12 gap-4 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:grid">
        <div class="col-span-4 pl-4">Client & Quote</div>
        <div class="col-span-2">Amount</div>
        <div class="col-span-2">Valid Until</div>
        <div class="col-span-2">Status</div>
        <div class="col-span-2 text-right pr-4">Actions</div>
    </div>

    @foreach($quotes as $quote)
    <div class="group bg-[#10231C]/60 rounded-2xl p-5 shadow-sm hover:shadow-md border border-white/10 transition-all duration-200 relative overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
             <!-- Status Stripe -->
            <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $quote->status === 'approved' ? 'bg-green-500' : ($quote->status === 'converted' ? 'bg-purple-500' : ($quote->status === 'sent' ? 'bg-blue-500' : ($quote->status === 'rejected' ? 'bg-red-500' : 'bg-gray-300'))) }}"></div>

            <!-- Client & Info -->
            <div class="col-span-12 md:col-span-4 flex items-center gap-4 pl-2">
                <div class="h-12 w-12 rounded-xl bg-white/[0.06] flex items-center justify-center text-lg font-bold text-gray-400 shadow-inner">
                    {{ substr($quote->client_name, 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                         <span class="text-sm font-mono font-medium text-gray-400 bg-white/[0.03] px-2 py-0.5 rounded border border-white/10">{{ $quote->quote_number }}</span>
                    </div>
                    <h3 class="text-base font-bold text-white line-clamp-1">{{ $quote->client_name }}</h3>
                    <p class="text-sm text-gray-400">{{ $quote->client_email }}</p>
                </div>
            </div>

            <!-- Amount -->
            <div class="col-span-6 md:col-span-2">
                <p class="text-xs text-gray-400 md:hidden mb-1">Total Amount</p>
                <div class="text-lg font-bold text-white">${{ number_format($quote->total_amount, 2) }}</div>
            </div>

            <!-- Due Date -->
            <div class="col-span-6 md:col-span-2">
                <p class="text-xs text-gray-400 md:hidden mb-1">Valid Until</p>
                <div class="flex items-center gap-2 text-sm text-gray-300">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    {{ $quote->valid_until->format('M d, Y') }}
                </div>
            </div>

            <!-- Status -->
            <div class="col-span-6 md:col-span-2">
                <p class="text-xs text-gray-400 md:hidden mb-1">Status</p>
                 @if($quote->status === 'approved')
                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">
                     <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span> Approved
                </span>
                @elseif($quote->status === 'converted')
                <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700 ring-1 ring-inset ring-purple-600/20">
                     <span class="h-1.5 w-1.5 rounded-full bg-purple-500"></span> Converted
                </span>
                @elseif($quote->status === 'sent')
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10">
                     <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span> Sent
                </span>
                @elseif($quote->status === 'rejected')
                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/10">
                     <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Rejected
                </span>
                @elseif($quote->status === 'expired')
                <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700 ring-1 ring-inset ring-orange-600/10">
                     <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span> Expired
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/[0.03] px-3 py-1 text-xs font-semibold text-gray-400 ring-1 ring-inset ring-gray-500/10">
                    <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span> Draft
                </span>
                @endif
            </div>

            <!-- Actions -->
            <div class="col-span-12 md:col-span-2 flex items-center justify-end gap-2 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">
                <a href="{{ route('admin.accounting.quotes.show', $quote) }}" class="p-2 text-gray-400 hover:text-brand-primary bg-white/[0.03] hover:bg-brand-primary/5 rounded-lg transition-colors border border-white/10" title="View Details">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
                <a href="{{ route('admin.accounting.quotes.edit', $quote) }}" class="p-2 text-gray-400 hover:text-brand-primary bg-white/[0.03] hover:bg-brand-primary/5 rounded-lg transition-colors border border-white/10" title="Edit">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </a>
                <form action="{{ route('admin.accounting.quotes.destroy', $quote) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this quote?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 bg-white/[0.03] hover:bg-red-50 rounded-lg transition-colors border border-white/10" title="Delete">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $quotes->links() }}
</div>
@endif
@endsection
