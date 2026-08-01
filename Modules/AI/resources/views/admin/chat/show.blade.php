@extends('admin::layouts.app')

@section('title', 'Conversation Details')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Conversation Details</h1>
        <p class="mt-2 text-sm text-gray-700">
            Started: {{ $conversation->created_at->format('M j, Y g:i A') }} | 
            Visitor: {{ $conversation->visitor_name ?? 'Guest' }}
        </p>
    </div>
    <a href="{{ route('admin.ai.chats.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
        Back to List
    </a>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Chat Transcript -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="divide-y divide-gray-200">
                @foreach($conversation->messages as $message)
                <div class="p-6 {{ $message->role === 'assistant' ? 'bg-gray-50' : '' }}">
                    <div class="flex space-x-3">
                        <div class="flex-shrink-0">
                            @if($message->role === 'assistant')
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand-primary">
                                    <span class="text-xs font-medium leading-none text-white">AI</span>
                                </span>
                            @else
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gray-500">
                                    <span class="text-xs font-medium leading-none text-white">U</span>
                                </span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                {{ ucfirst($message->role) }}
                            </p>
                            <div class="mt-1 text-sm text-gray-700">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                            <p class="mt-2 text-xs text-gray-400">
                                {{ $message->created_at->format('g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Metadata Sidepanel -->
    <div class="space-y-6">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Summary</h3>
            <p class="text-sm text-gray-700">{{ $conversation->summary ?? 'No summary generated yet.' }}</p>
        </div>

        <div class="bg-white shadow sm:rounded-lg p-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900 mb-4">Analysis</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Sentiment</dt>
                    <dd class="text-sm font-medium text-gray-900 capitalize">{{ $conversation->sentiment ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm text-gray-500">Total Messages</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $conversation->messages->count() }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
