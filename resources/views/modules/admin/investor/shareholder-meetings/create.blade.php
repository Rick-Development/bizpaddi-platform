@extends('admin::layouts.app')

@section('title', 'Create Shareholder Meeting')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Create Shareholder Meeting</h1>
    <p class="mt-2 text-sm text-gray-700">Schedule a new AGM, EGM, or special meeting</p>
</div>

<form action="{{ route('admin.investor.shareholder-meetings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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

                <div class="sm:col-span-2">
                    <label for="meeting_type" class="block text-sm font-medium text-gray-900">Meeting Type *</label>
                    <select name="meeting_type" id="meeting_type" required
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                        <option value="">Select Type</option>
                        <option value="agm" {{ old('meeting_type') == 'agm' ? 'selected' : '' }}>AGM (Annual General Meeting)</option>
                        <option value="egm" {{ old('meeting_type') == 'egm' ? 'selected' : '' }}>EGM (Extraordinary General Meeting)</option>
                        <option value="special" {{ old('meeting_type') == 'special' ? 'selected' : '' }}>Special Meeting</option>
                    </select>
                    @error('meeting_type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="meeting_date" class="block text-sm font-medium text-gray-900">Meeting Date & Time *</label>
                    <input type="datetime-local" name="meeting_date" id="meeting_date" value="{{ old('meeting_date') }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('meeting_date')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="location" class="block text-sm font-medium text-gray-900">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('location')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-900">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="agenda" class="block text-sm font-medium text-gray-900">Agenda</label>
                    <textarea name="agenda" id="agenda" rows="6"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('agenda') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Meeting agenda and topics</p>
                    @error('agenda')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Documents</h3>
                </div>

                <div class="sm:col-span-6">
                    <label for="agenda_file" class="block text-sm font-medium text-gray-900">Agenda File (PDF)</label>
                    <input type="file" name="agenda_file" id="agenda_file" accept=".pdf"
                           class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    <p class="mt-1 text-sm text-gray-500">PDF up to 5MB</p>
                    @error('agenda_file')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="minutes_file" class="block text-sm font-medium text-gray-900">Minutes File (PDF)</label>
                    <input type="file" name="minutes_file" id="minutes_file" accept=".pdf"
                           class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    <p class="mt-1 text-sm text-gray-500">PDF up to 5MB</p>
                    @error('minutes_file')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="presentation_file" class="block text-sm font-medium text-gray-900">Presentation (PDF/PPT)</label>
                    <input type="file" name="presentation_file" id="presentation_file" accept=".pdf,.ppt,.pptx"
                           class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    <p class="mt-1 text-sm text-gray-500">PDF or PowerPoint up to 10MB</p>
                    @error('presentation_file')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Status</h3>
                </div>

                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex h-6 items-center">
                            <input type="checkbox" name="is_upcoming" id="is_upcoming" value="1" {{ old('is_upcoming', true) ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_upcoming" class="font-medium text-gray-900">Mark as Upcoming</label>
                            <p class="text-gray-500">Display this meeting in the upcoming meetings section</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
            <a href="{{ route('admin.investor.shareholder-meetings.index') }}" class="text-sm font-semibold text-gray-900">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Create Meeting</button>
        </div>
    </div>
</form>
@endsection
