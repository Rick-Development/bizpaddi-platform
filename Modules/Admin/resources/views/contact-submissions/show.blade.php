@extends('admin::layouts.app')

@section('title', 'View Inquiry')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white">Inquiry Details</h1>
        <p class="mt-2 text-sm text-gray-300">From <span class="font-semibold">{{ $contactSubmission->name }}</span></p>
    </div>
    <div class="flex gap-x-3">
        <a href="{{ route('admin.contact-submissions.edit', $contactSubmission) }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            Edit Inquiry
        </a>
        <a href="{{ route('admin.contact-submissions.index') }}" class="rounded-md bg-[#10231C]/60 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-white/[0.03]">
            Back to List
        </a>
    </div>
</div>

<div class="overflow-hidden bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-base font-semibold leading-6 text-white">Message Information</h3>
    </div>
    <div class="border-t border-white/10 px-4 py-5 sm:p-0">
        <dl class="sm:divide-y sm:divide-white/10">
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-400">Full Name</dt>
                <dd class="mt-1 text-sm text-white sm:col-span-2 sm:mt-0">{{ $contactSubmission->name }}</dd>
            </div>
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-400">Email Address</dt>
                <dd class="mt-1 text-sm text-white sm:col-span-2 sm:mt-0">
                    <a href="mailto:{{ $contactSubmission->email }}" class="text-brand-primary hover:underline">{{ $contactSubmission->email }}</a>
                </dd>
            </div>
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-400">Phone Number</dt>
                <dd class="mt-1 text-sm text-white sm:col-span-2 sm:mt-0">
                    @if($contactSubmission->phone)
                        <a href="tel:{{ $contactSubmission->phone }}" class="text-brand-primary hover:underline">{{ $contactSubmission->phone }}</a>
                    @else
                        <span class="text-gray-400 italic">Not provided</span>
                    @endif
                </dd>
            </div>
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-400">Status</dt>
                <dd class="mt-1 text-sm text-white sm:col-span-2 sm:mt-0">
                    @if($contactSubmission->status === 'new')
                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">New</span>
                    @elseif($contactSubmission->status === 'in_progress')
                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-700/10">In Progress</span>
                    @else
                        <span class="inline-flex items-center rounded-md bg-white/[0.03] px-2 py-1 text-xs font-medium text-gray-400 ring-1 ring-inset ring-gray-500/10">Closed</span>
                    @endif
                </dd>
            </div>
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-400">Received On</dt>
                <dd class="mt-1 text-sm text-white sm:col-span-2 sm:mt-0">{{ $contactSubmission->created_at->format('F j, Y, g:i a') }}</dd>
            </div>
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-400">Subject</dt>
                <dd class="mt-1 text-sm text-white sm:col-span-2 sm:mt-0">{{ $contactSubmission->subject }}</dd>
            </div>
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-400">Message</dt>
                <dd class="mt-1 text-sm text-white sm:col-span-2 sm:mt-0 whitespace-pre-wrap">{{ $contactSubmission->message }}</dd>
            </div>
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-400">Admin Notes</dt>
                <dd class="mt-1 text-sm text-white sm:col-span-2 sm:mt-0 whitespace-pre-wrap">{{ $contactSubmission->notes ?: 'No notes added.' }}</dd>
            </div>
        </dl>
    </div>
    <div class="bg-white/[0.03] px-4 py-4 sm:px-6 flex justify-end gap-x-4">
        <a href="mailto:{{ $contactSubmission->email }}?subject=Re: {{ $contactSubmission->subject }}" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Reply via Email</a>
        <form action="{{ route('admin.contact-submissions.destroy', $contactSubmission) }}" method="POST" onsubmit="return confirm('Are you sure?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-md bg-[#10231C]/60 px-3 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50">Delete Inquiry</button>
        </form>
    </div>
</div>
@endsection
