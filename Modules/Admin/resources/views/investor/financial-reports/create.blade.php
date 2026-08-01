@extends('admin::layouts.app')

@section('title', 'Create Financial Report')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Create Financial Report</h1>
    <p class="mt-2 text-sm text-gray-700">Add a new quarterly or annual financial report</p>
</div>

<form action="{{ route('admin.investor.financial-reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-4xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="title" class="block text-sm font-medium text-gray-900">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="report_type" class="block text-sm font-medium text-gray-900">Report Type *</label>
                    <select name="report_type" id="report_type" required
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                        <option value="">Select Type</option>
                        <option value="quarterly" {{ old('report_type') == 'quarterly' ? 'selected' : '' }}>Quarterly Report</option>
                        <option value="annual" {{ old('report_type') == 'annual' ? 'selected' : '' }}>Annual Report</option>
                        <option value="special" {{ old('report_type') == 'special' ? 'selected' : '' }}>Special Report</option>
                    </select>
                    @error('report_type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="report_date" class="block text-sm font-medium text-gray-900">Report Date *</label>
                    <input type="date" name="report_date" id="report_date" value="{{ old('report_date') }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('report_date')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="fiscal_year" class="block text-sm font-medium text-gray-900">Fiscal Year *</label>
                    <input type="number" name="fiscal_year" id="fiscal_year" value="{{ old('fiscal_year', date('Y')) }}" min="2000" max="2100" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('fiscal_year')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="fiscal_quarter" class="block text-sm font-medium text-gray-900">Fiscal Quarter</label>
                    <select name="fiscal_quarter" id="fiscal_quarter"
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                        <option value="">N/A (for annual reports)</option>
                        <option value="1" {{ old('fiscal_quarter') == '1' ? 'selected' : '' }}>Q1</option>
                        <option value="2" {{ old('fiscal_quarter') == '2' ? 'selected' : '' }}>Q2</option>
                        <option value="3" {{ old('fiscal_quarter') == '3' ? 'selected' : '' }}>Q3</option>
                        <option value="4" {{ old('fiscal_quarter') == '4' ? 'selected' : '' }}>Q4</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Only for quarterly reports</p>
                    @error('fiscal_quarter')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="summary" class="block text-sm font-medium text-gray-900">Summary</label>
                    <textarea name="summary" id="summary" rows="4"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('summary') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Brief overview of the financial report</p>
                    @error('summary')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Report File</h3>
                </div>

                <div class="sm:col-span-6">
                    <label for="report_file" class="block text-sm font-medium text-gray-900">Upload Report (PDF, Excel)</label>
                    <input type="file" name="report_file" id="report_file" accept=".pdf,.xlsx,.xls"
                           class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    <p class="mt-1 text-sm text-gray-500">PDF, XLSX, or XLS up to 10MB</p>
                    @error('report_file')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Publishing</h3>
                </div>

                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex h-6 items-center">
                            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_published" class="font-medium text-gray-900">Publish immediately</label>
                            <p class="text-gray-500">Make this report publicly visible</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.investor.financial-reports.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Create Report</button>
        </div>
    </div>
</form>
@endsection
