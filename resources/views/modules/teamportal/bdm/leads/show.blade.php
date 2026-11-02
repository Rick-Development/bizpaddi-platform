@extends('teamportal::layouts.app')

@section('title', 'Lead Details: ' . $lead->name)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ route('team.bdm.leads.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $lead->name }}</h1>
            <p class="text-sm text-gray-500">{{ $lead->company ?? 'No Company' }}</p>
        </div>
    </div>
    
    <div class="flex items-center gap-3">
        @if($lead->status !== 'converted')
            <form action="{{ route('team.bdm.leads.update-status', $lead) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="converted">
                <button type="submit" onclick="return confirm('Are you sure you want to convert this lead?')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                    Convert Lead
                </button>
            </form>
        @endif

        <a href="{{ route('team.bdm.leads.edit', $lead) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Edit Lead
        </a>
        
        <form action="{{ route('team.bdm.leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead? This action cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                Delete
            </button>
        </form>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Lead Information -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Contact Information</h3>
            </div>
            <div class="px-6 py-5">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($lead->email)
                                <a href="mailto:{{ $lead->email }}" class="text-blue-600 hover:underline">{{ $lead->email }}</a>
                            @else
                                <span class="text-gray-400">Not provided</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($lead->phone)
                                <a href="tel:{{ $lead->phone }}" class="text-blue-600 hover:underline">{{ $lead->phone }}</a>
                            @else
                                <span class="text-gray-400">Not provided</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Source</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($lead->source)
                                @php
                                    $sourceModel = \Modules\Contact\Models\LeadSource::where('slug', $lead->source)->first();
                                @endphp
                                @if($sourceModel)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        <span class="w-2 h-2 rounded-full mr-1.5" style="background-color: {{ $sourceModel->color }}"></span>
                                        {{ $sourceModel->name }}
                                    </span>
                                @else
                                    <span class="capitalize">{{ str_replace('_', ' ', $lead->source) }}</span>
                                @endif
                            @else
                                <span class="text-gray-400">Unknown</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Lead Score</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <div class="flex items-center">
                                <span class="mr-2 font-semibold {{ $lead->score >= 70 ? 'text-green-600' : ($lead->score >= 40 ? 'text-yellow-600' : 'text-gray-600') }}">{{ $lead->score ?? 0 }}/100</span>
                                <div class="w-full bg-gray-200 rounded-full h-2 max-w-[100px]">
                                    <div class="h-2 rounded-full {{ $lead->score >= 70 ? 'bg-green-500' : ($lead->score >= 40 ? 'bg-yellow-500' : 'bg-gray-400') }}" style="width: {{ $lead->score ?? 0 }}%"></div>
                                </div>
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Deal Information -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Deal Information</h3>
            </div>
            <div class="px-6 py-5">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Deal Value</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">
                            @if($lead->value)
                                ${{ number_format($lead->value, 2) }}
                            @else
                                <span class="text-sm font-normal text-gray-400">Not specified</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Expected Close Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $lead->expected_close_date ? \Carbon\Carbon::parse($lead->expected_close_date)->format('M d, Y') : 'Not set' }}
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Notes & Background</dt>
                        <dd class="mt-2 text-sm text-gray-900 bg-gray-50 p-4 rounded-lg whitespace-pre-line border border-gray-100">
                            {{ $lead->notes ?: 'No notes available for this lead.' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Right Column: Status & Timeline -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Current Status</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('team.bdm.leads.update-status', $lead) }}" method="POST">
                    @csrf
                    <div class="flex items-center space-x-3 mb-4">
                        <select name="status" onchange="this.form.submit()" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6">
                            @foreach(['new' => 'New', 'contacted' => 'Contacted', 'nurturing' => 'Nurturing', 'qualified' => 'Qualified', 'converted' => 'Converted', 'lost' => 'Lost'] as $val => $label)
                                <option value="{{ $val }}" {{ $lead->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <div class="mt-4 pt-4 border-t border-gray-100 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Added on:</span>
                        <span class="text-gray-900">{{ $lead->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Last Contacted:</span>
                        <span class="text-gray-900">{{ $lead->last_contacted_at ? $lead->last_contacted_at->diffForHumans() : 'Never' }}</span>
                    </div>
                    @if($lead->converted_at)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Converted:</span>
                        <span class="text-gray-900 text-green-600">{{ $lead->converted_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Log Activity -->
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Log Interaction</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('team.bdm.leads.log-activity', $lead) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Interaction Type</label>
                            <select name="type" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6">
                                <option value="email">Email</option>
                                <option value="call">Phone Call</option>
                                <option value="meeting">Meeting</option>
                                <option value="note">Note / Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Details</label>
                            <textarea name="description" rows="3" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-emerald-500 sm:text-sm sm:leading-6" placeholder="What was discussed?"></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-500">
                            Save Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
