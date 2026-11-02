@extends('admin::layouts.app')

@section('title', 'Communication Scripts')

@section('content')
<div class="communication-scripts-page">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Communication Scripts</h1>
            <p class="mt-2 text-sm text-gray-300">Manage your communication scripts and playbooks.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.communication-scripts.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-brand-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 sm:w-auto">
                Create New Script
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-4">
        <form method="GET" action="{{ route('admin.communication-scripts.index') }}" class="flex items-end gap-4">
            <div class="flex-1">
                <label for="channel" class="block text-sm font-medium text-gray-300">Filter by Channel</label>
                <select name="channel_id" id="channel" class="mt-1 block w-full rounded-md border-white/10 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10" onchange="this.form.submit()">
                    <option value="">All Channels</option>
                    @foreach($channels as $channel)
                        <option value="{{ $channel->id }}" {{ request('channel_id') == $channel->id ? 'selected' : '' }}>
                            {{ $channel->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                 @if(request('channel_id'))
                    <a href="{{ route('admin.communication-scripts.index') }}" class="text-sm text-gray-400 hover:text-brand-primary underline">Clear Filter</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Scripts Grid -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($scripts as $script)
        <div class="bg-[#10231C]/60 overflow-hidden shadow rounded-lg flex flex-col">
            <div class="px-4 py-5 sm:p-6 flex-1">
                <div class="flex items-center justify-between mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $script->channel->name }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $script->is_active ? 'bg-green-100 text-green-800' : 'bg-white/[0.06] text-white' }}">
                        {{ $script->is_active ? 'Active' : 'Draft' }}
                    </span>
                </div>
                <h3 class="text-lg font-medium text-white truncate" title="{{ $script->title }}">
                    <a href="{{ route('admin.communication-scripts.show', $script) }}" class="hover:text-brand-primary focus:outline-none">
                        {{ $script->title }}
                    </a>
                </h3>
                <p class="mt-1 text-sm text-gray-400 line-clamp-3">
                    {{ $script->description ?: 'No description provided.' }}
                </p>
                <div class="mt-4 text-xs text-gray-400">
                    {{ $script->steps->count() }} Steps defined
                </div>
            </div>
            <div class="bg-white/[0.03] px-4 py-4 sm:px-6 flex justify-between items-center border-t border-white/10">
                <div class="flex gap-3">
                    <a href="{{ route('admin.communication-scripts.show', $script) }}" class="text-sm font-medium text-green-600 hover:text-green-900 flex items-center">
                        <svg class="mr-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Playbook
                    </a>
                    <a href="{{ route('admin.communication-scripts.edit', $script) }}" class="text-sm font-medium text-brand-primary hover:text-brand-primary/80">
                        Manage Steps
                    </a>
                </div>
                <div class="flex items-center gap-3">
                    <button @click="$dispatch('open-modal', 'edit-script-modal-{{ $script->id }}')" class="text-gray-400 hover:text-gray-400">
                        <span class="sr-only">Edit</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                    <form action="{{ route('admin.communication-scripts.destroy', $script->id) }}" method="POST" onsubmit="return confirm('Delete this script?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-500">
                            <span class="sr-only">Delete</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Edit Modal -->
            <x-modal name="edit-script-modal-{{ $script->id }}" title="Edit Script Details">
                <form action="{{ route('admin.communication-scripts.update', $script->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Title</label>
                            <input type="text" name="title" value="{{ $script->title }}" class="mt-1 block w-full rounded-md border-white/10 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300">Description</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-white/10 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">{{ $script->description }}</textarea>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ $script->is_active ? 'checked' : '' }} class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-white/10 rounded">
                            <label class="ml-2 block text-sm text-white">Active</label>
                        </div>
                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-brand-primary text-base font-medium text-white hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary sm:col-start-2 sm:text-sm">
                                Save Changes
                            </button>
                            <button type="button" @click="$dispatch('close-modal', 'edit-script-modal-{{ $script->id }}')" class="mt-3 w-full inline-flex justify-center rounded-md border border-white/10 shadow-sm px-4 py-2 bg-[#10231C]/60 text-base font-medium text-gray-300 hover:bg-white/[0.03] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary sm:mt-0 sm:col-start-1 sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </x-modal>
        </div>
        @empty
        <div class="col-span-full bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-white">No scripts found</h3>
            <p class="mt-1 text-sm text-gray-400">Get started by creating a new communication script.</p>
            <div class="mt-6">
                <a href="{{ route('admin.communication-scripts.create') }}" class="inline-flex items-center rounded-md border border-transparent bg-brand-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2">
                    Create Script
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $scripts->links() }}
    </div>
</div>
@endsection
