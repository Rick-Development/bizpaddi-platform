@extends('teamportal::layouts.app')

@section('title', 'Edit Lead Source')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('team.bdm.lead-sources.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Lead Source</h1>
            <p class="mt-1 text-sm text-gray-600">{{ $leadSource->name }}</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('team.bdm.lead-sources.update', $leadSource) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Source Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $leadSource->name) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-y">{{ old('description', $leadSource->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Badge Color (Hex)</label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="color-picker" value="{{ old('color', $leadSource->color) }}" class="h-10 w-10 border-0 rounded cursor-pointer">
                        <input type="text" name="color" id="color-hex" value="{{ old('color', $leadSource->color) }}" pattern="^#[0-9A-Fa-f]{6}$"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $leadSource->sort_order) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <!-- Hidden field to ensure '0' is sent if unchecked -->
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $leadSource->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-emerald-400 focus:ring-emerald-500">
                    <span class="text-sm font-medium text-gray-700">Active (available for selection)</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-x-3">
            <a href="{{ route('team.bdm.lead-sources.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                class="px-5 py-2 bg-emerald-600 text-white rounded-md text-sm font-semibold hover:bg-emerald-500 transition-colors">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const picker = document.getElementById('color-picker');
    const hexInput = document.getElementById('color-hex');

    picker.addEventListener('input', function() {
        hexInput.value = this.value.toUpperCase();
    });

    hexInput.addEventListener('input', function() {
        if (/^#[0-9A-F]{6}$/i.test(this.value)) {
            picker.value = this.value;
        }
    });
});
</script>
@endsection
