@extends('admin::layouts.app')

@section('title', 'Edit Financial Report')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Edit Financial Report</h1>
    <p class="mt-2 text-sm text-gray-700">Update financial report details</p>
</div>

<form action="{{ route('admin.investor.financial-reports.update', $financialReport) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-4xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="title" class="block text-sm font-medium text-gray-900">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $financialReport->title) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="report_type" class="block text-sm font-medium text-gray-900">Report Type *</label>
                    <select name="report_type" id="report_type" required
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                        <option value="">Select Type</option>
                        <option value="quarterly" {{ old('report_type', $financialReport->report_type) == 'quarterly' ? 'selected' : '' }}>Quarterly Report</option>
                        <option value="annual" {{ old('report_type', $financialReport->report_type) == 'annual' ? 'selected' : '' }}>Annual Report</option>
                        <option value="special" {{ old('report_type', $financialReport->report_type) == 'special' ? 'selected' : '' }}>Special Report</option>
                    </select>
                    @error('report_type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="report_date" class="block text-sm font-medium text-gray-900">Report Date *</label>
                    <input type="date" name="report_date" id="report_date" value="{{ old('report_date', $financialReport->report_date->format('Y-m-d')) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('report_date')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="fiscal_year" class="block text-sm font-medium text-gray-900">Fiscal Year *</label>
                    <input type="number" name="fiscal_year" id="fiscal_year" value="{{ old('fiscal_year', $financialReport->fiscal_year) }}" min="2000" max="2100" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('fiscal_year')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="fiscal_quarter" class="block text-sm font-medium text-gray-900">Fiscal Quarter</label>
                    <select name="fiscal_quarter" id="fiscal_quarter"
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                        <option value="">N/A (for annual reports)</option>
                        <option value="1" {{ old('fiscal_quarter', $financialReport->fiscal_quarter) == '1' ? 'selected' : '' }}>Q1</option>
                        <option value="2" {{ old('fiscal_quarter', $financialReport->fiscal_quarter) == '2' ? 'selected' : '' }}>Q2</option>
                        <option value="3" {{ old('fiscal_quarter', $financialReport->fiscal_quarter) == '3' ? 'selected' : '' }}>Q3</option>
                        <option value="4" {{ old('fiscal_quarter', $financialReport->fiscal_quarter) == '4' ? 'selected' : '' }}>Q4</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">Only for quarterly reports</p>
                    @error('fiscal_quarter')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="summary" class="block text-sm font-medium text-gray-900">Summary</label>
                    <textarea name="summary" id="summary" rows="4"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('summary', $financialReport->summary) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Brief overview of the financial report</p>
                    @error('summary')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Report File</h3>
                </div>

                @if($financialReport->getFirstMedia('report_file'))
                <div class="sm:col-span-6">
                    <div class="rounded-md bg-blue-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-blue-800">Current file:</p>
                                <p class="mt-1 text-sm text-blue-700">{{ $financialReport->getFirstMedia('report_file')->file_name }}</p>
                                <a href="{{ $financialReport->getFirstMediaUrl('report_file') }}" target="_blank" class="mt-2 inline-flex items-center text-sm font-medium text-blue-700 hover:text-blue-600">
                                    Download current file
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="sm:col-span-6">
                    <label for="report_file" class="block text-sm font-medium text-gray-900">{{ $financialReport->getFirstMedia('report_file') ? 'Replace Report File' : 'Upload Report' }}</label>
                    <input type="file" name="report_file" id="report_file" accept=".pdf,.xlsx,.xls"
                           class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    <p class="mt-1 text-sm text-gray-500">PDF, XLSX, or XLS up to 10MB. Leave empty to keep current file.</p>
                    @error('report_file')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Publishing</h3>
                </div>

                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex h-6 items-center">
                            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $financialReport->is_published) ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_published" class="font-medium text-gray-900">Published</label>
                            <p class="text-gray-500">Make this report publicly visible</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.investor.financial-reports.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Update Report</button>
        </div>
    </div>
</form>
@endsection
