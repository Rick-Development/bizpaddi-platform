@extends('clientportal::layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('client.tickets.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">#{{ $ticket->id }} - {{ $ticket->subject }}</h1>
            <p class="mt-1 text-sm text-gray-600">
                Created {{ $ticket->created_at->format('M d, Y \a\t h:i A') }}
            </p>
        </div>
        <div class="ml-auto flex items-center gap-3">
             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : 
                   ($ticket->status === 'resolved' ? 'bg-gray-100 text-gray-800' : 
                   ($ticket->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                {{ ucfirst($ticket->status) }}
            </span>
             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                {{ $ticket->priority === 'high' || $ticket->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                   ($ticket->priority === 'medium' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') }}">
                {{ ucfirst($ticket->priority) }}
            </span>
        </div>
    </div>
</div>

<div class="max-w-3xl space-y-6">
    <!-- Messages Feed -->
    <div class="space-y-6">
        @foreach($ticket->messages as $message)
            @if($message->sender_type === get_class(auth()->guard('client')->user()) && $message->sender_id === auth()->guard('client')->id())
                <!-- Client Message (Right) -->
                <div class="flex flex-col items-end">
                    <div class="flex items-end gap-2 max-w-[85%]">
                        <div class="bg-brand-primary text-white rounded-2xl rounded-tr-none px-5 py-3 shadow-sm">
                            <p class="text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                        </div>
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-brand-primary/20 flex items-center justify-center text-brand-primary text-xs font-bold">
                            You
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 mt-1 mr-10">{{ $message->created_at->format('M d, h:i A') }}</span>
                </div>
            @else
                <!-- Admin/Support Message (Left) -->
                <div class="flex flex-col items-start">
                    <div class="flex items-end gap-2 max-w-[85%]">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-xs font-bold">
                            S
                        </div>
                        <div class="bg-white border border-gray-100 text-gray-800 rounded-2xl rounded-tl-none px-5 py-3 shadow-sm">
                            <p class="text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 mt-1 ml-10">{{ $message->created_at->format('M d, h:i A') }}</span>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Reply Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-8">
        <h3 class="text-sm font-medium text-gray-900 mb-4">Add a Reply</h3>
        <form action="{{ route('client.tickets.reply', $ticket->id) }}" method="POST">
            @csrf
            <div>
                <label for="message" class="sr-only">Message</label>
                <textarea id="message" name="message" rows="4" class="block w-full shadow-sm focus:ring-brand-primary focus:border-brand-primary sm:text-sm border-gray-300 rounded-xl p-4" placeholder="Write your reply here..." required></textarea>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl shadow-sm text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                    Send Reply
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
