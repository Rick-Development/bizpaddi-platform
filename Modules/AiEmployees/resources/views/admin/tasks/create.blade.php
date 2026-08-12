@extends('admin::layouts.app')

@section('title', 'Assign AI Task')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.aiemployees.tasks.index') }}" class="inline-flex items-center text-sm font-semibold text-brand-primary hover:text-brand-primary/80 transition-colors mb-4">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
            Back to Assignments
        </a>
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Assign New AI Task</h1>
        <p class="mt-2 text-md text-gray-600">Assign an autonomous mandate to one of your active AI Employees.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.aiemployees.tasks.store') }}">
            @csrf
            
            <div class="space-y-6">
                <!-- AI Employee Select -->
                <div>
                    <label for="employee_id" class="block text-sm font-bold text-slate-700 mb-2">Select AI Employee</label>
                    <select name="employee_id" id="employee_id" class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-1 focus:ring-brand-primary bg-white @error('employee_id') border-red-300 @enderror">
                        <option value="">Choose an active AI Employee...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }} ({{ str_replace('_', ' ', Str::title($emp->role)) }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Task description -->
                <div>
                    <label for="task" class="block text-sm font-bold text-slate-700 mb-2">Task Details & Mandate</label>
                    <textarea name="task" id="task" rows="8" class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-1 focus:ring-brand-primary @error('task') border-red-300 @enderror" placeholder="Describe the task in detail. The AI employee will read this along with its system instructions, execute any necessary tools autonomously, and write the output. E.g., 'Draft a LinkedIn announcement for our next project', or 'Generate weekly invoices for client ABC'...">{{ old('task') }}</textarea>
                    @error('task')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.aiemployees.tasks.index') }}" class="rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 px-5 py-3 text-sm font-semibold transition-all">Cancel</a>
                <button type="submit" class="rounded-xl bg-brand-primary hover:bg-brand-primary/95 text-white px-5 py-3 text-sm font-semibold shadow-md shadow-brand-primary/20 transition-all">Assign & Queue Task</button>
            </div>
        </form>
    </div>
</div>
@endsection
