@extends('teamportal::layouts.app')

@section('title', 'Create Contract')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('team.bdm.contracts.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Contract</h1>
            <p class="mt-1 text-sm text-gray-600">Create a new contract or agreement</p>
        </div>
    </div>
</div>

<form action="{{ route('team.bdm.contracts.store') }}" method="POST" class="space-y-6">
    @csrf
    @if(isset($order))
        <input type="hidden" name="order_id" value="{{ $order->id }}">
    @endif

    @if(isset($order))
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    Creating contract from <strong>Order #{{ $order->id }}</strong>. Details have been pre-filled.
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', isset($order) ? ($order->service->name . ' - Agreement') : '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contract Type <span class="text-red-500">*</span></label>
                <select name="contract_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="">Select Type</option>
                    <option value="sla" {{ old('contract_type') == 'sla' ? 'selected' : '' }}>SLA (Service Level Agreement)</option>
                    <option value="nda" {{ old('contract_type') == 'nda' ? 'selected' : '' }}>NDA (Non-Disclosure Agreement)</option>
                    <option value="employment" {{ old('contract_type') == 'employment' ? 'selected' : '' }}>Employment Contract</option>
                    <option value="service_agreement" {{ old('contract_type', isset($order) ? 'service_agreement' : '') == 'service_agreement' ? 'selected' : '' }}>Service Agreement</option>
                    <option value="partnership" {{ old('contract_type') == 'partnership' ? 'selected' : '' }}>Partnership Agreement</option>
                    <option value="vendor" {{ old('contract_type') == 'vendor' ? 'selected' : '' }}>Vendor Agreement</option>
                    <option value="other" {{ old('contract_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('contract_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Party Information</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Party Type</label>
                <select name="party_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="">Select Type</option>
                    <option value="client" {{ old('party_type', isset($order) ? 'client' : '') == 'client' ? 'selected' : '' }}>Client</option>
                    <option value="employee" {{ old('party_type') == 'employee' ? 'selected' : '' }}>Employee</option>
                    <option value="vendor" {{ old('party_type') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                    <option value="partner" {{ old('party_type') == 'partner' ? 'selected' : '' }}>Partner</option>
                    <option value="other" {{ old('party_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Party Name <span class="text-red-500">*</span></label>
                <input type="text" name="party_name" value="{{ old('party_name', isset($order) ? $order->client->name : '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                @error('party_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Party Email</label>
                <input type="email" name="party_email" value="{{ old('party_email', isset($order) ? $order->client->email : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Party Address</label>
                <input type="text" name="party_address" value="{{ old('party_address', isset($order) ? $order->client->address : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Contract Duration & Value</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
            </div>
            
            <!-- ... existing duration fields ... -->
            <!-- We will skip exact line matching for unchanged middle fields if possible, or include them -->
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration (Months)</label>
                <input type="number" name="duration_months" value="{{ old('duration_months') }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration (Weeks)</label>
                <input type="number" name="duration_weeks" value="{{ old('duration_weeks') }}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                <p class="mt-1 text-xs text-gray-500">For contracts shorter than a month or to add weeks to months</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contract Value</label>
                <input type="number" name="value" value="{{ old('value', isset($order) ? ($order->quote->total ?? ($order->invoice->total ?? '')) : '') }}" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                <select name="currency_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    <option value="">Select Currency</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                            {{ $currency->code }} ({{ $currency->symbol }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="auto_renew" value="1" {{ old('auto_renew') ? 'checked' : '' }} class="h-4 w-4 text-[#FB4C4B] focus:ring-[#FB4C4B] border-gray-300 rounded">
                <label class="ml-2 block text-sm text-gray-700">Auto-renew contract</label>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Contract Content</h2>
        
        @if($templates->count() > 0)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Load from Template (Optional)</label>
            <select id="template-selector" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                <option value="">-- Select a template --</option>
                @foreach($templates as $template)
                    <option value="{{ $template->id }}">{{ $template->name }} ({{ strtoupper($template->contract_type) }})</option>
                @endforeach
            </select>
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contract Body <span class="text-red-500">*</span></label>
            <textarea name="content" id="contract-content" required>{{ old('content') }}</textarea>
            <p class="mt-1 text-sm text-gray-500">Available placeholders: @{{ party_name }}, @{{ start_date }}, @{{ end_date }}, @{{ value }}, etc.</p>
            @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
            <textarea name="terms" id="terms-content">{{ old('terms') }}</textarea>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Internal Notes</label>
            <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">{{ old('notes') }}</textarea>
        </div>
    </div>

    <div class="flex justify-end gap-x-3">
        <a href="{{ route('team.bdm.contracts.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
            Cancel
        </a>
        <button type="submit" class="px-4 py-2 bg-[#FB4C4B] text-white rounded-md text-sm font-medium hover:bg-[#D93837]">
            Create Contract
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

    // Template loader
    $('#template-selector').on('change', async function() {
        if (!this.value) return;
        
        try {
            const response = await fetch('{{ route("admin.contracts.load-template") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ template_id: this.value })
            });
            
            const data = await response.json();
            if (data.content) {
                $('#contract-content').summernote('code', data.content);
            }
            if (data.terms) {
                $('#terms-content').summernote('code', data.terms);
            }
        } catch (error) {
            console.error('Error loading template:', error);
        }
    });
});
</script>
@endsection
