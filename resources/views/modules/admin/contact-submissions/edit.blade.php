@extends('admin::layouts.app')

@section('title', 'Edit Inquiry')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Edit Inquiry</h1>
        <p class="mt-2 text-sm text-gray-700">Update status and notes for <span class="font-semibold">{{ $contactSubmission->name }}</span></p>
    </div>
    <a href="{{ route('admin.contact-submissions.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
        Back to List
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white shadow sm:rounded-lg">
            <form action="{{ route('admin.contact-submissions.update', $contactSubmission) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="px-4 py-5 sm:p-6 space-y-6">
                    <div>
                        <label for="status" class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                        <select id="status" name="status" class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="new" {{ $contactSubmission->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="in_progress" {{ $contactSubmission->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="closed" {{ $contactSubmission->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium leading-6 text-gray-900">Admin Notes</label>
                        <div class="mt-2">
                            <textarea id="notes" name="notes" rows="6" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ old('notes', $contactSubmission->notes) }}</textarea>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-gray-600">Internal notes about this inquiry. Not visible to the user.</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-end">
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Update Inquiry
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Reference Info</h3>
            </div>
            <div class="px-4 py-5 sm:p-6 space-y-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $contactSubmission->subject }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Original Message</p>
                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap italic bg-gray-50 p-3 rounded-md">{{ Str::limit($contactSubmission->message, 300) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
