@extends('admin::layouts.app')

@section('title', 'Edit Lead')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.leads.show', $lead) }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Edit Lead</h1>
            <p class="text-sm text-gray-500">{{ $lead->name }}</p>
        </div>
    </div>
</div>

<form action="{{ route('admin.leads.update', $lead) }}" method="POST" class="max-w-4xl">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
        <!-- Basic Information -->
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $lead->name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $lead->email) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $lead->phone) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>

                <!-- Company -->
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                    <input type="text" name="company" id="company" value="{{ old('company', $lead->company) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Lead Details -->
        <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Lead Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="nurturing" {{ old('status', $lead->status) == 'nurturing' ? 'selected' : '' }}>Nurturing</option>
                        <option value="qualified" {{ old('status', $lead->status) == 'qualified' ? 'selected' : '' }}>Qualified</option>
                        <option value="converted" {{ old('status', $lead->status) == 'converted' ? 'selected' : '' }}>Converted</option>
                        <option value="lost" {{ old('status', $lead->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>

                <!-- Source -->
                <div>
                    <label for="source" class="block text-sm font-medium text-gray-700 mb-2">Source</label>
                    <input type="text" name="source" id="source" value="{{ old('source', $lead->source) }}" placeholder="e.g., Website Form, Referral"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>

                <!-- Score -->
                <div>
                    <label for="score" class="block text-sm font-medium text-gray-700 mb-2">Lead Score (0-100)</label>
                    <input type="number" name="score" id="score" value="{{ old('score', $lead->score) }}" min="0" max="100"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                </div>

                <!-- Assigned To -->
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                    <select name="assigned_to" id="assigned_to"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <option value="">Unassigned</option>
                        @foreach($teamMembers as $member)
                            <option value="{{ $member->id }}" {{ old('assigned_to', $lead->assigned_to) == $member->id ? 'selected' : '' }}>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="border-t border-gray-200 pt-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea name="notes" id="notes" rows="4" placeholder="Add any additional notes about this lead..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">{{ old('notes', $lead->notes) }}</textarea>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between border-t border-gray-200 pt-6">
            <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 text-red-600 hover:text-red-800">
                    Delete Lead
                </button>
            </form>

            <div class="flex items-center gap-4">
                <a href="{{ route('admin.leads.show', $lead) }}" class="px-6 py-2 text-gray-700 hover:text-gray-900">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition">
                    Update Lead
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
