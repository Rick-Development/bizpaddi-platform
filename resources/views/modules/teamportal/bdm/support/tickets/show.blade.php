@extends('teamportal::layouts.app')

@section('title', 'Ticket #' . $ticket->id)

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Ticket #{{ $ticket->id }}</h1>
            <p class="mt-2 text-sm text-gray-600">Submitted by {{ $ticket->client->name }} on {{ $ticket->created_at->format('M d, Y H:i') }}</p>
        </div>
        <div class="flex items-center gap-3">
             <form action="{{ route('team.bdm.tickets.update', $ticket->id) }}" method="POST" class="flex items-center gap-2">
                @csrf
                @method('PUT')
                <select name="status" onchange="this.form.submit()" class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-brand-primary focus:border-brand-primary sm:text-sm rounded-md">
                    @foreach(['open', 'in_progress', 'resolved', 'closed'] as $status)
                        <option value="{{ $status }}" {{ $ticket->status === $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </form>
             <a href="{{ route('team.bdm.tickets.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Back to List</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Subject/Initial Message (Uses description if exists or just subject) -->
             <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                 <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $ticket->subject }}</h3>
                 <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $ticket->subject }} </div>
             </div>

            <!-- Messages Thread -->
            <div class="space-y-4">
                @foreach($ticket->messages as $message)
                    <div class="flex {{ $message->is_admin ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[85%] {{ $message->is_admin ? 'bg-brand-primary text-white border-transparent' : 'bg-gray-100 text-gray-900 border-gray-200' }} rounded-2xl px-6 py-4 shadow-sm border">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold {{ $message->is_admin ? 'text-white' : 'text-gray-900' }}">
                                    {{ $message->is_admin ? 'Admin' : $ticket->client->name }}
                                </span>
                                <span class="text-xs {{ $message->is_admin ? 'text-blue-100' : 'text-gray-500' }} ml-4">{{ $message->created_at->format('M d, H:i') }}</span>
                            </div>
                            <div class="text-sm {{ $message->is_admin ? 'text-white' : 'text-gray-800' }} whitespace-pre-wrap">{{ $message->message }}</div>
                             @if($message->attachments && count($message->attachments) > 0)
                                <div class="mt-3 pt-3 border-t border-gray-200/50">
                                    <p class="text-xs font-medium text-gray-500 mb-1">Attachments:</p>
                                    <ul class="space-y-1">
                                        @foreach($message->attachments as $attachment)
                                            <li>
                                                <a href="{{ Storage::url($attachment) }}" target="_blank" class="text-xs text-brand-primary hover:underline flex items-center">
                                                    <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                                    View Attachment
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reply Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reply to Ticket</h3>
                <form action="{{ route('team.bdm.tickets.reply', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <textarea name="message" id="ticket-reply-message" rows="4" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Type your reply here..." required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                            Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
         <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Client Details</h3>
                <div class="flex items-center mb-4">
                     <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                        {{ strtoupper(substr($ticket->client->name, 0, 2)) }}
                    </div>
                    <div class="ml-3">
                         <p class="text-sm font-medium text-gray-900">{{ $ticket->client->name }}</p>
                        <p class="text-xs text-gray-500">{{ $ticket->client->email }}</p>
                    </div>
                </div>
                 <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Company</dt>
                        <dd class="font-medium text-gray-900">{{ $ticket->client->company_name ?? 'N/A' }}</dd>
                    </div>
                     <div class="flex justify-between">
                        <dt class="text-gray-500">Phone</dt>
                        <dd class="font-medium text-gray-900">{{ $ticket->client->phone ?? 'N/A' }}</dd>
                    </div>
                </dl>
                 <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('team.bdm.clients.show', $ticket->client->id) }}" class="text-sm text-brand-primary hover:underline">View Full Profile</a>
                </div>
            </div>
        </div>
    </div>
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#ticket-reply-message').summernote({
            placeholder: 'Type your reply here...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
@endpush
@endsection
