@extends('admin::layouts.app')

@section('title', 'Edit Contract: ' . $contract->contract_number)

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.contracts.show', $contract) }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900">Edit Contract</h1>
            <p class="mt-1 text-sm text-gray-600">{{ $contract->contract_number }} - {{ $contract->title }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.contracts.pdf', $contract) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                </svg>
                PDF
            </a>
            <a href="{{ route('admin.contracts.show', $contract) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                View
            </a>
        </div>
    </div>
</div>

<form action="{{ route('admin.contracts.update', $contract) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Basic Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $contract->title) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contract Type <span class="text-red-500">*</span></label>
                    <select name="contract_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                        <option value="">Select Type</option>
                        <option value="sla" {{ old('contract_type', $contract->contract_type) == 'sla' ? 'selected' : '' }}>SLA (Service Level Agreement)</option>
                        <option value="nda" {{ old('contract_type', $contract->contract_type) == 'nda' ? 'selected' : '' }}>NDA (Non-Disclosure Agreement)</option>
                        <option value="employment" {{ old('contract_type', $contract->contract_type) == 'employment' ? 'selected' : '' }}>Employment Contract</option>
                        <option value="service_agreement" {{ old('contract_type', $contract->contract_type) == 'service_agreement' ? 'selected' : '' }}>Service Agreement</option>
                        <option value="partnership" {{ old('contract_type', $contract->contract_type) == 'partnership' ? 'selected' : '' }}>Partnership Agreement</option>
                        <option value="vendor" {{ old('contract_type', $contract->contract_type) == 'vendor' ? 'selected' : '' }}>Vendor Agreement</option>
                        <option value="other" {{ old('contract_type', $contract->contract_type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('contract_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Party Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Party Information</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Party Type</label>
                    <select name="party_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                        <option value="">Select Type</option>
                        <option value="client" {{ old('party_type', $contract->party_type) == 'client' ? 'selected' : '' }}>Client</option>
                        <option value="employee" {{ old('party_type', $contract->party_type) == 'employee' ? 'selected' : '' }}>Employee</option>
                        <option value="vendor" {{ old('party_type', $contract->party_type) == 'vendor' ? 'selected' : '' }}>Vendor</option>
                        <option value="partner" {{ old('party_type', $contract->party_type) == 'partner' ? 'selected' : '' }}>Partner</option>
                        <option value="other" {{ old('party_type', $contract->party_type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Party Name <span class="text-red-500">*</span></label>
                    <input type="text" name="party_name" value="{{ old('party_name', $contract->party_name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                    @error('party_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Party Email</label>
                    <input type="email" name="party_email" value="{{ old('party_email', $contract->party_email) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Party Address</label>
                    <input type="text" name="party_address" value="{{ old('party_address', $contract->party_address) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                </div>
            </div>
        </div>
    </div>

    <!-- Contract Duration & Value -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Contract Duration & Value</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" value="{{ old('start_date', $contract->start_date?->format('Y-m-d')) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $contract->end_date?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration (Months)</label>
                    <input type="number" name="duration_months" value="{{ old('duration_months', $contract->duration_months) }}" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration (Weeks)</label>
                    <input type="number" name="duration_weeks" value="{{ old('duration_weeks', $contract->duration_weeks) }}" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                    <p class="mt-1 text-xs text-gray-500">For contracts shorter than a month or to add weeks to months</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contract Value</label>
                    <input type="number" name="value" value="{{ old('value', $contract->value) }}" step="0.01" min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                    <select name="currency_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all">
                        <option value="">Select Currency</option>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}" {{ old('currency_id', $contract->currency_id) == $currency->id ? 'selected' : '' }}>
                                {{ $currency->code }} ({{ $currency->symbol }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center space-x-6 md:col-span-2 lg:col-span-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="auto_renew" value="1" {{ old('auto_renew', $contract->auto_renew) ? 'checked' : '' }} class="h-4 w-4 text-[#FB4C4B] focus:ring-[#FB4C4B] border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Auto-renew contract</span>
                    </label>
                    
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $contract->is_active) ? 'checked' : '' }} class="h-4 w-4 text-[#FB4C4B] focus:ring-[#FB4C4B] border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Contract is Active</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Contract Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Contract Content</h2>
            <p class="text-sm text-gray-600 mt-1">Use the WordPress-style editor to format your contract content</p>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contract Body <span class="text-red-500">*</span></label>
                <textarea name="content" id="contract-content" required>{{ old('content', $contract->content) }}</textarea>
                <p class="mt-2 text-sm text-gray-500">
                    <span class="font-medium">Available placeholders:</span> 
                    <code class="px-2 py-0.5 bg-gray-100 rounded text-xs">@{{ party_name }}</code>
                    <code class="px-2 py-0.5 bg-gray-100 rounded text-xs">@{{ start_date }}</code>
                    <code class="px-2 py-0.5 bg-gray-100 rounded text-xs">@{{ end_date }}</code>
                    <code class="px-2 py-0.5 bg-gray-100 rounded text-xs">@{{ value }}</code>
                </p>
                @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Terms & Conditions</label>
                <textarea name="terms" id="terms-content">{{ old('terms', $contract->terms) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Internal Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent transition-all" placeholder="Add any internal notes or comments...">{{ old('notes', $contract->notes) }}</textarea>
            </div>
        </div>
    </div>
    
    <!-- Warning Alert -->
    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border-l-4 border-yellow-400 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700 font-medium">
                    Updating the contract content will automatically create a new version in the version history.
                </p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
        <a href="{{ route('admin.contracts.show', $contract) }}" class="inline-flex items-center px-6 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Cancel
        </a>
        <button type="submit" class="inline-flex items-center px-8 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[#FB4C4B] to-[#D93837] hover:from-[#D93837] hover:to-[#FB4C4B] shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Update Contract
        </button>
    </div>
</form>

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Summernote for contract content
    $('#contract-content').summernote({
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        placeholder: 'Enter contract content here...',
        tabsize: 2
    });

    // Initialize Summernote for terms
    $('#terms-content').summernote({
        height: 250,
        toolbar: [
            ['font', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['view', ['codeview']]
        ],
        placeholder: 'Enter terms and conditions...',
        tabsize: 2
    });
});
</script>
@endsection

