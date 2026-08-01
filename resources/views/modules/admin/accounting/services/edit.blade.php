@extends('admin::layouts.app')

@section('title', 'Edit Service')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.accounting.services.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Service</h1>
            <p class="mt-1 text-sm text-gray-600">Update service details</p>
        </div>
    </div>
</div>

<div class="max-w-2xl" x-data="{
    currencyId: '{{ old('currency_id', $service->currency_id) }}',
    currencySymbol: '$',
    updateSymbol(e) {
        if (!this.currencyId) {
            this.currencySymbol = '$'; // Default
            return;
        }
        const selectedOption = e.target.options[e.target.selectedIndex];
        this.currencySymbol = selectedOption.getAttribute('data-symbol') || '$';
    },
    init() {
        // Init symbol based on current selection
        this.$nextTick(() => {
            const select = document.getElementById('currency_id');
            if (select && select.value) {
                const selectedOption = select.options[select.selectedIndex];
                this.currencySymbol = selectedOption.getAttribute('data-symbol') || '$';
            }
        });
    }
}">
    <form action="{{ route('admin.accounting.services.update', $service) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-200">
        @csrf
        @method('PUT')
        
        <div class="p-6 space-y-6">
            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                <select name="category_id" id="category_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('category_id') border-red-500 @enderror">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $service->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Service Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('description') border-red-500 @enderror">{{ old('description', $service->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Currency Selection -->
            <div>
                <label for="currency_id" class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                <select name="currency_id" id="currency_id" x-model="currencyId" @change="updateSymbol"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('currency_id') border-red-500 @enderror">
                    <option value="">Default (System Default)</option>
                    @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}" data-symbol="{{ $currency->symbol }}">
                        {{ $currency->code }} - {{ $currency->name }} ({{ $currency->symbol }})
                    </option>
                    @endforeach
                </select>
                @error('currency_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price and Unit -->
            <!-- Price and Unit -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="base_price" class="block text-sm font-medium text-gray-700 mb-2">Base Price *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm" x-text="currencySymbol"></span>
                        </div>
                        <input type="number" name="base_price" id="base_price" value="{{ old('base_price', $service->base_price) }}" step="0.01" min="0" required
                            class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('base_price') border-red-500 @enderror">
                    </div>
                    @error('base_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit *</label>
                    <select name="unit" id="unit" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('unit') border-red-500 @enderror">
                        <option value="hour" {{ old('unit', $service->unit) == 'hour' ? 'selected' : '' }}>Hour</option>
                        <option value="day" {{ old('unit', $service->unit) == 'day' ? 'selected' : '' }}>Day</option>
                        <option value="project" {{ old('unit', $service->unit) == 'project' ? 'selected' : '' }}>Project</option>
                        <option value="item" {{ old('unit', $service->unit) == 'item' ? 'selected' : '' }}>Item</option>
                        <option value="month" {{ old('unit', $service->unit) == 'month' ? 'selected' : '' }}>Month</option>
                    </select>
                    @error('unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Active Status -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                    class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-700">
                    Active (available for quotes and invoices)
                </label>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-2xl flex items-center justify-end gap-x-3">
            <a href="{{ route('admin.accounting.services.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-brand-primary rounded-lg hover:bg-brand-primary/90 transition-colors">
                Update Service
            </button>
        </div>
    </form>
</div>
@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame { border: 1px solid #d1d5db !important; border-radius: 0.5rem !important; overflow: hidden; }
    .note-toolbar { background: #f9fafb !important; border-bottom: 1px solid #d1d5db !important; }
</style>
@endpush

@push('scripts')
<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    $('#description').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        placeholder: 'Enter service description here...',
        tabsize: 2
    });
});
</script>
@endpush
@endsection
