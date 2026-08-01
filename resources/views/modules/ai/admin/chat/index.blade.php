@extends('admin::layouts.app')

@section('title', 'AI Conversations')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold text-gray-900">Conversations</h1>
        <p class="mt-2 text-sm text-gray-700">A list of all AI interactions with users and visitors.</p>
    </div>
</div>

<div class="mt-8 flex flex-col">
    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">User/Visitor</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Started</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Messages</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Summary</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">View</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($conversations as $conversation)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                {{ $conversation->visitor_name ?? 'Guest Visitor' }}
                                <br>
                                <span class="text-xs text-gray-500">{{ $conversation->visitor_email ?? 'No Email' }}</span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $conversation->created_at->format('M j, Y g:i A') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                {{ $conversation->messages_count }}
                            </td>
                            <td class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate">
                                {{ Str::limit($conversation->summary ?? 'No summary available', 50) }}
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('admin.ai.chats.show', $conversation->id) }}" class="text-brand-primary hover:text-brand-primary/80">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-3 py-4 text-sm text-center text-gray-500">No conversations found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $conversations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
