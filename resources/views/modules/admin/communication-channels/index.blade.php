@extends('admin::layouts.app')

@section('title', 'Communication Channels')

@section('content')
<div class="communication-channels-page">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Communication Channels</h1>
            <p class="mt-2 text-sm text-gray-700">Manage the channels used for your communication scripts.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button @click="$dispatch('open-modal', 'create-channel-modal')" class="inline-flex items-center justify-center rounded-md border border-transparent bg-brand-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 sm:w-auto">
                Add Channel
            </button>
        </div>
    </div>

    <!-- Channels List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($channels as $channel)
            <li>
                <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                            @if($channel->icon)
                                <i class="{{ $channel->icon }}"></i>
                            @else
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $channel->name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $channel->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $channel->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="$dispatch('open-modal', 'edit-channel-modal-{{ $channel->id }}')" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                        <form action="{{ route('admin.communication-channels.destroy', $channel->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                        </form>
                    </div>
                </div>
                
                <!-- Edit Modal -->
                <x-modal name="edit-channel-modal-{{ $channel->id }}" title="Edit Channel">
                    <form action="{{ route('admin.communication-channels.update', $channel->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name" value="{{ $channel->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" required>
                            </div>
                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700">Icon Class (optional)</label>
                                <input type="text" name="icon" id="icon" value="{{ $channel->icon }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active_{{ $channel->id }}" value="1" {{ $channel->is_active ? 'checked' : '' }} class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-gray-300 rounded">
                                <label for="is_active_{{ $channel->id }}" class="ml-2 block text-sm text-gray-900">Active</label>
                            </div>
                            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-brand-primary text-base font-medium text-white hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary sm:col-start-2 sm:text-sm">
                                    Update
                                </button>
                                <button type="button" @click="$dispatch('close-modal', 'edit-channel-modal-{{ $channel->id }}')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary sm:mt-0 sm:col-start-1 sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </x-modal>
            </li>
            @empty
            <li class="px-4 py-8 text-center text-gray-500">
                No channels found. create one to get started.
            </li>
            @endforelse
        </ul>
    </div>

    <!-- Create Modal -->
    <x-modal name="create-channel-modal" title="Add New Channel">
        <form action="{{ route('admin.communication-channels.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="new_name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="new_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" required placeholder="e.g. Cold Call, WhatsApp">
                </div>
                <div>
                    <label for="new_icon" class="block text-sm font-medium text-gray-700">Icon Class (optional)</label>
                    <input type="text" name="icon" id="new_icon" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" placeholder="e.g. fas fa-phone">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="new_is_active" value="1" checked class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-gray-300 rounded">
                    <label for="new_is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-brand-primary text-base font-medium text-white hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary sm:col-start-2 sm:text-sm">
                        Create
                    </button>
                    <button type="button" @click="$dispatch('close-modal', 'create-channel-modal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary sm:mt-0 sm:col-start-1 sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </form>
    </x-modal>
</div>
@endsection
