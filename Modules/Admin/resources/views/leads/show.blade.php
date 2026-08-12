@extends('admin::layouts.app')

@section('title', $lead->name)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-x-4">
            <a href="{{ route('admin.leads.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $lead->name }}</h1>
                <p class="text-sm text-gray-500">Lead Details</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($lead->status !== 'converted')
                <form action="{{ route('admin.leads.convert', $lead) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Convert to Contact
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.leads.edit', $lead) }}" class="px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition">
                Edit Lead
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Lead Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Lead Information</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->phone ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Company</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->company ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Source</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->source ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->created_at->format('M d, Y h:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Contacted</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->last_contacted_at ? $lead->last_contacted_at->format('M d, Y h:i A') : '-' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Tracking Data -->
        @if($lead->utm_source || $lead->utm_medium || $lead->utm_campaign || $lead->utm_term || $lead->utm_content)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Tracking Data (UTM)</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($lead->utm_source)
                <div>
                    <dt class="text-sm font-medium text-gray-500">UTM Source</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->utm_source }}</dd>
                </div>
                @endif
                @if($lead->utm_medium)
                <div>
                    <dt class="text-sm font-medium text-gray-500">UTM Medium</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->utm_medium }}</dd>
                </div>
                @endif
                @if($lead->utm_campaign)
                <div>
                    <dt class="text-sm font-medium text-gray-500">UTM Campaign</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->utm_campaign }}</dd>
                </div>
                @endif
                @if($lead->utm_term)
                <div>
                    <dt class="text-sm font-medium text-gray-500">UTM Term</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->utm_term }}</dd>
                </div>
                @endif
                @if($lead->utm_content)
                <div>
                    <dt class="text-sm font-medium text-gray-500">UTM Content</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->utm_content }}</dd>
                </div>
                @endif
            </dl>
        </div>
        @endif

        <!-- Company Details -->
        @if($lead->company_details)
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-brand-primary">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Company Details & Research</h2>
                <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $lead->company_details }}</p>
            </div>
        @endif

        <!-- Notes -->
        @if($lead->notes)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Notes</h2>
                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $lead->notes }}</p>
            </div>
        @endif

        <!-- Conversion Info -->
        @if($lead->status === 'converted' && $lead->convertedToContact)
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-green-900 mb-2">Converted to Contact</h2>
                <p class="text-sm text-green-700 mb-4">
                    This lead was converted on {{ $lead->converted_at->format('M d, Y h:i A') }}
                </p>
                <a href="{{ route('admin.contacts.show', $lead->converted_to_contact_id) }}" class="inline-flex items-center text-sm text-green-700 hover:text-green-900">
                    View Contact
                    <svg class="ml-1 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-3">Status</h3>
            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                @if($lead->status == 'new') bg-blue-100 text-blue-800
                @elseif($lead->status == 'contacted') bg-yellow-100 text-yellow-800
                @elseif($lead->status == 'nurturing') bg-purple-100 text-purple-800
                @elseif($lead->status == 'qualified') bg-green-100 text-green-800
                @elseif($lead->status == 'converted') bg-emerald-100 text-emerald-800
                @else bg-red-100 text-red-800
                @endif">
                {{ ucfirst($lead->status) }}
            </span>

            @if($lead->status !== 'converted')
                <form action="{{ route('admin.leads.update-status', $lead) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">Change Status...</option>
                        <option value="new">New</option>
                        <option value="contacted">Contacted</option>
                        <option value="nurturing">Nurturing</option>
                        <option value="qualified">Qualified</option>
                        <option value="lost">Lost</option>
                    </select>
                </form>
            @endif
        </div>

        <!-- Lead Score -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-3">Lead Score</h3>
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl font-bold text-gray-900">{{ $lead->score }}</span>
                <span class="text-sm text-gray-500">/100</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-brand-primary h-2 rounded-full" style="width: {{ $lead->score }}%"></div>
            </div>
        </div>

        <!-- Assignment -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-500 mb-3">Assigned To</h3>
            @if($lead->assignedTo)
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-brand-primary text-white flex items-center justify-center font-semibold">
                        {{ substr($lead->assignedTo->name, 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ $lead->assignedTo->name }}</p>
                        <p class="text-xs text-gray-500">{{ $lead->assignedTo->email }}</p>
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-500">Not assigned</p>
            @endif
        </div>

        <!-- Quick Actions -->
        @if($lead->status !== 'converted')
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Quick Actions</h3>
                <div class="space-y-2">
                    <form action="{{ route('admin.leads.mark-contacted', $lead) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-50 rounded-lg transition">
                            Mark as Contacted
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
