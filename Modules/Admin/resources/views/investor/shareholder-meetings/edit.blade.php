@extends('admin::layouts.app')

@section('title', 'Edit Shareholder Meeting')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Edit Shareholder Meeting</h1>
    <p class="mt-2 text-sm text-gray-700">Update meeting details and documents</p>
</div>

<form action="{{ route('admin.investor.shareholder-meetings.update', $shareholderMeeting) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
        <div class="px-4 py-6 sm:p-8">
            <div class="grid max-w-4xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="title" class="block text-sm font-medium text-gray-900">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $shareholderMeeting->title) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="meeting_type" class="block text-sm font-medium text-gray-900">Meeting Type *</label>
                    <select name="meeting_type" id="meeting_type" required
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                        <option value="">Select Type</option>
                        <option value="agm" {{ old('meeting_type', $shareholderMeeting->meeting_type) == 'agm' ? 'selected' : '' }}>AGM (Annual General Meeting)</option>
                        <option value="egm" {{ old('meeting_type', $shareholderMeeting->meeting_type) == 'egm' ? 'selected' : '' }}>EGM (Extraordinary General Meeting)</option>
                        <option value="special" {{ old('meeting_type', $shareholderMeeting->meeting_type) == 'special' ? 'selected' : '' }}>Special Meeting</option>
                    </select>
                    @error('meeting_type')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="meeting_date" class="block text-sm font-medium text-gray-900">Meeting Date & Time *</label>
                    <input type="datetime-local" name="meeting_date" id="meeting_date" value="{{ old('meeting_date', $shareholderMeeting->meeting_date->format('Y-m-d\TH:i')) }}" required
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('meeting_date')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="location" class="block text-sm font-medium text-gray-900">Location</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $shareholderMeeting->location) }}"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                    @error('location')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="description" class="block text-sm font-medium text-gray-900">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('description', $shareholderMeeting->description) }}</textarea>
                    @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="agenda" class="block text-sm font-medium text-gray-900">Agenda</label>
                    <textarea name="agenda" id="agenda" rows="6"
                              class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm">{{ old('agenda', $shareholderMeeting->agenda) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Meeting agenda and topics</p>
                    @error('agenda')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Documents</h3>
                </div>

                @if($shareholderMeeting->getFirstMedia('agenda_file'))
                <div class="sm:col-span-6">
                    <div class="rounded-md bg-blue-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-blue-800">Current agenda file: {{ $shareholderMeeting->getFirstMedia('agenda_file')->file_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="sm:col-span-6">
                    <label for="agenda_file" class="block text-sm font-medium text-gray-900">{{ $shareholderMeeting->getFirstMedia('agenda_file') ? 'Replace Agenda File' : 'Agenda File' }} (PDF)</label>
                    <input type="file" name="agenda_file" id="agenda_file" accept=".pdf"
                           class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    <p class="mt-1 text-sm text-gray-500">PDF up to 5MB. Leave empty to keep current file.</p>
                    @error('agenda_file')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                @if($shareholderMeeting->getFirstMedia('minutes_file'))
                <div class="sm:col-span-6">
                    <div class="rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-green-800">Current minutes file: {{ $shareholderMeeting->getFirstMedia('minutes_file')->file_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="sm:col-span-6">
                    <label for="minutes_file" class="block text-sm font-medium text-gray-900">{{ $shareholderMeeting->getFirstMedia('minutes_file') ? 'Replace Minutes File' : 'Minutes File' }} (PDF)</label>
                    <input type="file" name="minutes_file" id="minutes_file" accept=".pdf"
                           class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    <p class="mt-1 text-sm text-gray-500">PDF up to 5MB. Leave empty to keep current file.</p>
                    @error('minutes_file')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                @if($shareholderMeeting->getFirstMedia('presentation_file'))
                <div class="sm:col-span-6">
                    <div class="rounded-md bg-purple-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-purple-800">Current presentation: {{ $shareholderMeeting->getFirstMedia('presentation_file')->file_name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="sm:col-span-6">
                    <label for="presentation_file" class="block text-sm font-medium text-gray-900">{{ $shareholderMeeting->getFirstMedia('presentation_file') ? 'Replace Presentation' : 'Presentation' }} (PDF/PPT)</label>
                    <input type="file" name="presentation_file" id="presentation_file" accept=".pdf,.ppt,.pptx"
                           class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                    <p class="mt-1 text-sm text-gray-500">PDF or PowerPoint up to 10MB. Leave empty to keep current file.</p>
                    @error('presentation_file')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-6 border-t border-gray-200 pt-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Status</h3>
                </div>

                <div class="sm:col-span-6">
                    <div class="flex items-start">
                        <div class="flex h-6 items-center">
                            <input type="checkbox" name="is_upcoming" id="is_upcoming" value="1" {{ old('is_upcoming', $shareholderMeeting->is_upcoming) ? 'checked' : '' }}
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
            <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Update Meeting</button>
        </div>
    </div>
</form>
@endsection
