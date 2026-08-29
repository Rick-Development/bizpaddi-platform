@extends('admin::layouts.app')

@section('title', 'Application Details')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Application Details</h1>
        <p class="mt-2 text-sm text-gray-700">Applied for <span class="font-semibold">{{ $application->job->title }}</span></p>
    </div>
    <a href="{{ route('admin.applications.index') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
        Back to List
    </a>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Candidate Info -->
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Candidate Information</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $application->candidate->name }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <a href="mailto:{{ $application->candidate->email }}" class="text-brand-primary hover:underline">{{ $application->candidate->email }}</a>
                        </dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $application->candidate->phone ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Applied On</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $application->created_at->format('F j, Y, g:i a') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Cover Letter -->
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Cover Letter</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <div class="prose prose-sm max-w-none text-gray-900">
                    {!! nl2br(e($application->cover_letter)) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Management -->
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Application Status</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <form action="{{ route('admin.applications.update', $application) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="status" onchange="this.form.submit()"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm sm:leading-6">
                        <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewing" {{ $application->status === 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                        <option value="interview" {{ $application->status === 'interview' ? 'selected' : '' }}>Interview</option>
                        <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- AI Analysis -->
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">AI Analysis</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                @if($application->ai_score)
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-gray-500">Match Score</span>
                        <span class="text-2xl font-bold {{ $application->ai_score >= 80 ? 'text-green-600' : ($application->ai_score >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $application->ai_score }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-brand-primary h-2.5 rounded-full" style="width: {{ $application->ai_score }}%"></div>
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic">AI analysis pending...</p>
                @endif
            </div>
        </div>

        <!-- Resume Download -->
        @if($application->resume_path)
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Documents</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <a href="{{ route('admin.applications.download-resume', $application) }}" target="_blank" class="flex items-center gap-x-3 text-sm font-semibold text-brand-primary hover:text-red-700">
                    <svg class="h-5 w-5 flex-none text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                    </svg>
                    Download Resume
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
