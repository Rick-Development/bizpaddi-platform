@extends('admin::layouts.app')

@section('title', 'Post New Job')

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        border: 1px solid #e5e7eb !important;
        border-radius: 0.75rem !important;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .note-editor.note-frame.codeview .note-editable {
        display: none;
    }
    .note-toolbar {
        background-color: #f9fafb !important;
        border-bottom: 1px solid #e5e7eb !important;
        padding: 8px 12px !important;
    }
    .note-btn {
        background: white !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        color: #374151 !important;
        font-size: 13px !important;
        padding: 5px 10px !important;
    }
    .note-btn:hover {
        background: #f3f4f6 !important;
    }
    .note-btn.active {
        background: #e5e7eb !important;
        color: #111827 !important;
    }
    .note-editable {
        font-family: inherit;
        font-size: 14px;
        line-height: 1.6;
        color: #1f2937;
        padding: 16px !important;
        min-height: 200px;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-16">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.jobs.index') }}" class="hover:text-brand-primary transition-colors">Jobs</a>
                <span>/</span>
                <span class="text-gray-800 font-medium">Create New Job</span>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Post New Career Opportunity</h1>
            <p class="mt-1 text-sm text-gray-500">Create a rich job posting with complete details, requirements, and benefits.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.jobs.index') }}" class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors shadow-sm">
                Cancel
            </a>
            <button type="submit" form="job-form" class="inline-flex items-center px-5 py-2.5 bg-brand-primary text-white text-sm font-semibold rounded-xl hover:bg-brand-primary/90 shadow-lg shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Publish Job
            </button>
        </div>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="rounded-xl bg-red-50 p-4 border border-red-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-red-800">Please review the form errors:</h3>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form id="job-form" action="{{ route('admin.jobs.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- 1. Basic Role Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-brand-primary/10 text-brand-primary font-bold text-sm">1</span>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Basic Role Information</h2>
                        <p class="text-xs text-gray-500">Core position details and work environment</p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-6 gap-6">
                    <div class="sm:col-span-4">
                        <label for="title" class="block text-sm font-semibold text-gray-900">Job Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               placeholder="e.g., Software Quality Assurance (QA) Tester, Senior Frontend Engineer"
                               class="mt-2 block w-full rounded-xl border-gray-300 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                        @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="department" class="block text-sm font-semibold text-gray-900">Department <span class="text-red-500">*</span></label>
                        <input type="text" name="department" id="department" value="{{ old('department') }}" required
                               list="department-suggestions"
                               placeholder="e.g., Engineering, Quality Assurance, Design"
                               class="mt-2 block w-full rounded-xl border-gray-300 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                        <datalist id="department-suggestions">
                            <option value="Quality Assurance / Engineering">
                            <option value="Software Engineering">
                            <option value="Product & Design">
                            <option value="Data & AI">
                            <option value="Project Management">
                            <option value="Sales & Marketing">
                            <option value="Operations & Support">
                        </datalist>
                        @error('department')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="location" class="block text-sm font-semibold text-gray-900">Primary Location / Region <span class="text-red-500">*</span></label>
                        <div class="relative mt-2">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <input type="text" name="location" id="location" value="{{ old('location', 'Remote, Worldwide') }}" required
                                   placeholder="e.g., Remote, Nigeria, London, UK, or Hybrid (Lagos)"
                                   class="block w-full rounded-xl border-gray-300 pl-10 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                        </div>
                        @error('location')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="workplace_type" class="block text-sm font-semibold text-gray-900">Workplace Type</label>
                        <select name="workplace_type" id="workplace_type"
                                class="mt-2 block w-full rounded-xl border-gray-300 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                            <option value="remote" {{ old('workplace_type', 'remote') === 'remote' ? 'selected' : '' }}>Remote (Work from Anywhere / Home)</option>
                            <option value="hybrid" {{ old('workplace_type') === 'hybrid' ? 'selected' : '' }}>Hybrid (Office + Remote)</option>
                            <option value="on-site" {{ old('workplace_type') === 'on-site' ? 'selected' : '' }}>On-site (Office based)</option>
                        </select>
                        @error('workplace_type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="type" class="block text-sm font-semibold text-gray-900">Employment Type <span class="text-red-500">*</span></label>
                        <select name="type" id="type" required
                                class="mt-2 block w-full rounded-xl border-gray-300 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                            <option value="full-time" {{ old('type', 'full-time') === 'full-time' ? 'selected' : '' }}>Full-Time</option>
                            <option value="part-time" {{ old('type') === 'part-time' ? 'selected' : '' }}>Part-Time</option>
                            <option value="contract" {{ old('type') === 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="remote" {{ old('type') === 'remote' ? 'selected' : '' }}>Remote</option>
                            <option value="internship" {{ old('type') === 'internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                        @error('type')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="experience_level" class="block text-sm font-semibold text-gray-900">Experience Level</label>
                        <select name="experience_level" id="experience_level"
                                class="mt-2 block w-full rounded-xl border-gray-300 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                            <option value="">Select Level</option>
                            <option value="Entry-Level" {{ old('experience_level') === 'Entry-Level' ? 'selected' : '' }}>Entry-Level / Junior (0-2 years)</option>
                            <option value="Mid-Level" {{ old('experience_level', 'Mid-Level') === 'Mid-Level' ? 'selected' : '' }}>Mid-Level (2-5 years)</option>
                            <option value="Senior" {{ old('experience_level') === 'Senior' ? 'selected' : '' }}>Senior (5+ years)</option>
                            <option value="Lead / Principal" {{ old('experience_level') === 'Lead / Principal' ? 'selected' : '' }}>Lead / Principal</option>
                            <option value="Executive" {{ old('experience_level') === 'Executive' ? 'selected' : '' }}>Executive / Director</option>
                            <option value="Internship" {{ old('experience_level') === 'Internship' ? 'selected' : '' }}>Internship</option>
                        </select>
                        @error('experience_level')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Compensation & Timeline -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-sm">2</span>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Compensation & Deadlines</h2>
                        <p class="text-xs text-gray-500">Salary structure, currency, and application timeframe</p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                    <div>
                        <label for="currency" class="block text-sm font-semibold text-gray-900">Currency</label>
                        <select name="currency" id="currency"
                                class="mt-2 block w-full rounded-xl border-gray-300 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                            <option value="NGN" {{ old('currency', 'NGN') === 'NGN' ? 'selected' : '' }}>NGN (₦) - Nigerian Naira</option>
                            <option value="USD" {{ old('currency') === 'USD' ? 'selected' : '' }}>USD ($) - US Dollar</option>
                            <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR (€) - Euro</option>
                            <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>GBP (£) - British Pound</option>
                            <option value="CAD" {{ old('currency') === 'CAD' ? 'selected' : '' }}>CAD (CA$) - Canadian Dollar</option>
                        </select>
                        @error('currency')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="salary_min" class="block text-sm font-semibold text-gray-900">Minimum Salary / Amount</label>
                        <input type="number" step="0.01" name="salary_min" id="salary_min" value="{{ old('salary_min') }}"
                               placeholder="e.g., 100000"
                               class="mt-2 block w-full rounded-xl border-gray-300 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Leave blank if undisclosed</p>
                        @error('salary_min')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="salary_max" class="block text-sm font-semibold text-gray-900">Maximum Salary (Optional)</label>
                        <input type="number" step="0.01" name="salary_max" id="salary_max" value="{{ old('salary_max') }}"
                               placeholder="e.g., 150000"
                               class="mt-2 block w-full rounded-xl border-gray-300 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Provide for range (e.g. 100k - 150k)</p>
                        @error('salary_max')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="salary_period" class="block text-sm font-semibold text-gray-900">Frequency</label>
                        <select name="salary_period" id="salary_period"
                                class="mt-2 block w-full rounded-xl border-gray-300 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                            <option value="month" {{ old('salary_period', 'month') === 'month' ? 'selected' : '' }}>Per Month</option>
                            <option value="year" {{ old('salary_period') === 'year' ? 'selected' : '' }}>Per Year</option>
                            <option value="hour" {{ old('salary_period') === 'hour' ? 'selected' : '' }}>Per Hour</option>
                            <option value="project" {{ old('salary_period') === 'project' ? 'selected' : '' }}>Per Project</option>
                        </select>
                        @error('salary_period')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="application_deadline" class="block text-sm font-semibold text-gray-900">Application Deadline</label>
                        <input type="date" name="application_deadline" id="application_deadline" value="{{ old('application_deadline') }}"
                               class="mt-2 block w-full rounded-xl border-gray-300 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Leave blank for rolling/ongoing applications</p>
                        @error('application_deadline')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="skills" class="block text-sm font-semibold text-gray-900">Key Skills & Tags (Comma Separated)</label>
                        <input type="text" name="skills" id="skills" value="{{ old('skills') }}"
                               placeholder="e.g., Postman, Swagger, Manual Testing, APK Testing, Android, REST APIs"
                               class="mt-2 block w-full rounded-xl border-gray-300 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                        <div id="skills-chips" class="mt-2 flex flex-wrap gap-1.5 min-h-[24px]"></div>
                        @error('skills')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Rich Content with WYSIWYG Editors -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 font-bold text-sm">3</span>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Role Details & Content (WYSIWYG)</h2>
                        <p class="text-xs text-gray-500">Format text with rich styles, bullet lists, bold text, and links</p>
                    </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Summernote Editor Enabled
                </span>
            </div>

            <div class="p-6 sm:p-8 space-y-8">
                <!-- Description / Overview -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="description" class="block text-sm font-semibold text-gray-900">
                            About the Role & Overview <span class="text-red-500">*</span>
                        </label>
                        <span class="text-xs text-gray-500">Overview, team mission, reporting hierarchy</span>
                    </div>
                    <textarea name="description" id="description" class="summernote-editor" required>{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Key Responsibilities -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="responsibilities" class="block text-sm font-semibold text-gray-900">
                            Key Responsibilities & Deliverables
                        </label>
                        <span class="text-xs text-gray-500">Day-to-day duties and core focus areas</span>
                    </div>
                    <textarea name="responsibilities" id="responsibilities" class="summernote-editor">{{ old('responsibilities') }}</textarea>
                    @error('responsibilities')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Requirements & Qualifications -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="requirements" class="block text-sm font-semibold text-gray-900">
                            Requirements & Qualifications <span class="text-red-500">*</span>
                        </label>
                        <span class="text-xs text-gray-500">Required background, technical skills, tools & experience</span>
                    </div>
                    <textarea name="requirements" id="requirements" class="summernote-editor" required>{{ old('requirements') }}</textarea>
                    @error('requirements')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Benefits & Perks -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="benefits" class="block text-sm font-semibold text-gray-900">
                            What We Offer / Benefits & Perks
                        </label>
                        <span class="text-xs text-gray-500">Compensation perks, work flexibility, equipment, professional growth</span>
                    </div>
                    <textarea name="benefits" id="benefits" class="summernote-editor">{{ old('benefits') }}</textarea>
                    @error('benefits')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- 4. Publishing & Visibility -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-purple-50 text-purple-600 font-bold text-sm">4</span>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Publishing & Visibility</h2>
                        <p class="text-xs text-gray-500">Control active status, featured badge, and application availability</p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-900">Listing Status</label>
                        <select name="status" id="status"
                                class="mt-2 block w-full rounded-xl border-gray-300 py-2.5 px-3.5 text-gray-900 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                            <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published (Live)</option>
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                            <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed (Applications paused)</option>
                        </select>
                        @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2 flex flex-col sm:flex-row gap-6 sm:items-center pt-2">
                        <label class="relative flex items-start gap-3 p-4 rounded-xl border border-gray-200 hover:border-brand-primary cursor-pointer transition-all bg-gray-50/30 flex-1">
                            <div class="flex h-5 items-center">
                                <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                            </div>
                            <div class="text-xs leading-5">
                                <span class="font-semibold text-gray-900 block text-sm">Active Listing</span>
                                <span class="text-gray-500">Visible on public careers portal immediately.</span>
                            </div>
                        </label>

                        <label class="relative flex items-start gap-3 p-4 rounded-xl border border-gray-200 hover:border-amber-400 cursor-pointer transition-all bg-amber-50/20 flex-1">
                            <div class="flex h-5 items-center">
                                <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                            </div>
                            <div class="text-xs leading-5">
                                <span class="font-semibold text-gray-900 block text-sm">Featured Job</span>
                                <span class="text-gray-500">Highlight this role at the top of the careers page.</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="flex items-center justify-end gap-x-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.jobs.index') }}" class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-brand-primary text-white text-sm font-semibold rounded-xl hover:bg-brand-primary/90 shadow-lg shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Publish Job
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- jQuery (required for Summernote) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Summernote for all rich editors
    $('.summernote-editor').summernote({
        height: 220,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'hr']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        placeholder: 'Write formatted details here...'
    });

    // Interactive Skills Chips
    function updateSkillsChips() {
        const input = $('#skills').val();
        const container = $('#skills-chips');
        container.empty();
        if (!input) return;

        const skills = input.split(',').map(s => s.trim()).filter(s => s.length > 0);
        skills.forEach(skill => {
            const badge = $(`<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-brand-primary/10 text-brand-primary border border-brand-primary/20">
                ${skill}
            </span>`);
            container.append(badge);
        });
    }

    $('#skills').on('input', updateSkillsChips);
    updateSkillsChips();
});
</script>
@endpush
