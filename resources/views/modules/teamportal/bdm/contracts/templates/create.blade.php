@extends('teamportal::layouts.app')

@section('title', 'Create Contract Template')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('team.bdm.contracts.templates.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Template</h1>
            <p class="mt-1 text-sm text-gray-600">Create a reusable contract template</p>
        </div>
    </div>
</div>

<form action="{{ route('team.bdm.contracts.templates.store') }}" method="POST" class="space-y-6">
    @csrf

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Template Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contract Type <span class="text-red-500">*</span></label>
                <select name="contract_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <option value="">Select Type</option>
                    <option value="sla" {{ old('contract_type') == 'sla' ? 'selected' : '' }}>SLA (Service Level Agreement)</option>
                    <option value="nda" {{ old('contract_type') == 'nda' ? 'selected' : '' }}>NDA (Non-Disclosure Agreement)</option>
                    <option value="employment" {{ old('contract_type') == 'employment' ? 'selected' : '' }}>Employment Contract</option>
                    <option value="service_agreement" {{ old('contract_type') == 'service_agreement' ? 'selected' : '' }}>Service Agreement</option>
                    <option value="partnership" {{ old('contract_type') == 'partnership' ? 'selected' : '' }}>Partnership Agreement</option>
                    <option value="vendor" {{ old('contract_type') == 'vendor' ? 'selected' : '' }}>Vendor Agreement</option>
                    <option value="other" {{ old('contract_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('contract_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-end">
                <div class="flex items-center mb-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 text-emerald-400 focus:ring-emerald-500 border-gray-300 rounded">
                    <label class="ml-2 block text-sm text-gray-700">Active</label>
                </div>
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Template Content</h2>
        
        <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Content Body <span class="text-red-500">*</span></label>
            <textarea name="content" rows="15" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-mono text-sm">{{ old('content') }}</textarea>
            <p class="mt-1 text-sm text-gray-500">Available placeholders: @{{ party_name }}, @{{ start_date }}, @{{ end_date }}, @{{ value }}, @{{ contract_number }}, etc.</p>
            @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="col-span-2 mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Default Terms & Conditions</label>
            <textarea name="default_terms" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">{{ old('default_terms') }}</textarea>
        </div>
    </div>

    <div class="flex justify-end gap-x-3">
        <a href="{{ route('team.bdm.contracts.templates.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md text-sm font-medium hover:bg-emerald-500">
            Create Template
        </button>
    </div>
</form>
@endsection
