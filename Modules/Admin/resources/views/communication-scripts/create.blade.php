@extends('admin::layouts.app')

@section('title', 'Create Script')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Create New Script</h1>
        <p class="mt-1 text-sm text-gray-400">Define the basic details for your communication script.</p>
    </div>

    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl">
        <form action="{{ route('admin.communication-scripts.store') }}" method="POST" class="space-y-6 p-6">
            @csrf
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="channel_id" class="block text-sm font-medium text-gray-300">Channel</label>
                    <div class="mt-1">
                        <select id="channel_id" name="channel_id" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-white/10 rounded-md bg-[#0a1612] text-white border-white/10" required>
                            <option value="">Select a channel...</option>
                            @foreach($channels as $channel)
                                <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="mt-2 text-sm text-gray-400">e.g. Cold Call, Email, WhatsApp</p>
                </div>

                <div class="sm:col-span-6">
                    <label for="title" class="block text-sm font-medium text-gray-300">Script Title</label>
                    <div class="mt-1">
                        <input type="text" name="title" id="title" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-white/10 rounded-md bg-[#0a1612] text-white border-white/10" required placeholder="e.g. First Contact - Real Estate">
                    </div>
                </div>

                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                    <div class="mt-1">
                        <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border border-white/10 rounded-md bg-[#0a1612] text-white border-white/10" placeholder="Briefly describe the purpose of this script..."></textarea>
                    </div>
                </div>

                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_active" name="is_active" type="checkbox" value="1" checked class="focus:ring-brand-primary h-4 w-4 text-brand-primary border-white/10 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-gray-300">Activate Immediately</label>
                            <p class="text-gray-400">If unchecked, this script will be saved as a draft.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-5">
                <a href="{{ route('admin.communication-scripts.index') }}" class="bg-[#10231C]/60 py-2 px-4 border border-white/10 rounded-md shadow-sm text-sm font-medium text-gray-300 hover:bg-white/[0.03] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                    Cancel
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                    Create Script
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
