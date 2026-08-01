@extends('admin::layouts.app')

@section('title', 'Send Notifications')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Send Push Notifications</h1>
    <p class="mt-2 text-sm text-gray-700">Send notifications to selected clients via Firebase Cloud Messaging.</p>
</div>

@if(session('success'))
    <div class="mb-6 rounded-md bg-green-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 rounded-md bg-red-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif

<form action="{{ route('admin.notifications.send') }}" method="POST" class="space-y-8">
    @csrf
    
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                
                <!-- Title -->
                <div class="sm:col-span-6">
                    <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Notification Title</label>
                    <div class="mt-2">
                        <input type="text" name="title" id="title" required
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6"
                               placeholder="e.g., New Feature Available">
                    </div>
                </div>

                <!-- Message -->
                <div class="sm:col-span-6">
                    <label for="message" class="block text-sm font-medium leading-6 text-gray-900">Message</label>
                    <div class="mt-2">
                        <textarea name="message" id="message" rows="4" required
                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6"
                                  placeholder="Enter your notification message here..."></textarea>
                    </div>
                </div>

                <!-- Client Selection -->
                <div class="sm:col-span-6">
                    <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Select Recipients</label>
                    
                    <div class="mb-3 flex gap-2">
                        <button type="button" onclick="selectAll()" class="text-sm px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded">Select All</button>
                        <button type="button" onclick="deselectAll()" class="text-sm px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded">Deselect All</button>
                    </div>

                    <div class="max-h-96 overflow-y-auto border border-gray-300 rounded-md p-4 space-y-2">
                        @forelse($clients as $client)
                            <div class="flex items-center">
                                <input type="checkbox" name="client_ids[]" value="{{ $client->id }}" id="client_{{ $client->id }}"
                                       class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary client-checkbox">
                                <label for="client_{{ $client->id }}" class="ml-3 text-sm text-gray-700 cursor-pointer">
                                    {{ $client->name }} 
                                    @if($client->company_name)
                                        <span class="text-gray-500">({{ $client->company_name }})</span>
                                    @endif
                                    <span class="text-gray-400">- {{ $client->email }}</span>
                                </label>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No active clients found.</p>
                        @endforelse
                    </div>
                    @error('client_ids')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>
        
        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <button type="button" onclick="window.history.back()" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                Send Notification
            </button>
        </div>
    </div>
</form>

<script>
function selectAll() {
    document.querySelectorAll('.client-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAll() {
    document.querySelectorAll('.client-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
}
</script>
@endsection
