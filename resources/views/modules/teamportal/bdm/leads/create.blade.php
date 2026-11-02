@extends('teamportal::layouts.app')

@section('title', 'Record New Lead')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('team.bdm.leads.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Record New Lead</h1>
            <p class="mt-1 text-sm text-gray-600">Capture a potential client or opportunity</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('team.bdm.leads.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-5">
            <h2 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Lead Information</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="e.g. John Doe">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="john@example.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="+44 7700 900000">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company / Organisation</label>
                    <input type="text" name="company" value="{{ old('company') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="e.g. Acme Ltd">
                </div>
            </div>
        </div>

        {{-- Deal Details --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-5">
            <h2 class="text-base font-semibold text-gray-900 border-b border-gray-100 pb-3">Deal Details</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        @foreach(['new' => 'New', 'contacted' => 'Contacted', 'nurturing' => 'Nurturing', 'qualified' => 'Qualified', 'converted' => 'Converted', 'lost' => 'Lost'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', 'new') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                    <select name="source" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                        <option value="">— Select source —</option>
                        @foreach($sources as $src)
                            <option value="{{ $src->slug }}" {{ old('source') === $src->slug ? 'selected' : '' }}>{{ $src->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deal Value ($)</label>
                    <input type="number" name="value" value="{{ old('value') }}" min="0" step="0.01"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                        placeholder="0.00">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expected Close Date</label>
                    <input type="date" name="expected_close_date" value="{{ old('expected_close_date') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lead Score (0–100)</label>
                    <input type="number" name="score" value="{{ old('score', 50) }}" min="0" max="100"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-y"
                    placeholder="Any additional context, background, or initial conversation notes...">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-x-3">
            <a href="{{ route('team.bdm.leads.index') }}"
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex items-center px-5 py-2 bg-emerald-600 text-white rounded-md text-sm font-semibold hover:bg-emerald-500 transition-colors">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Record Lead
            </button>
        </div>
    </form>
</div>
@endsection
