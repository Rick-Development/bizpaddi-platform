@extends('admin::layouts.app')

@section('title', $lead->name)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-x-4">
            <a href="{{ route('admin.leads.index') }}" class="text-gray-400 hover:text-gray-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-semibold text-white">{{ $lead->name }}</h1>
                <p class="text-sm text-gray-400">Lead Details</p>
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
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-sm p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Lead Information</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-400">Email</dt>
                    <dd class="mt-1 text-sm text-white">{{ $lead->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-400">Phone</dt>
                    <dd class="mt-1 text-sm text-white">{{ $lead->phone ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-400">Company</dt>
                    <dd class="mt-1 text-sm text-white">{{ $lead->company ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-400">Source</dt>
                    <dd class="mt-1 text-sm text-white">{{ $lead->source ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-400">Created</dt>
                    <dd class="mt-1 text-sm text-white">{{ $lead->created_at->format('M d, Y h:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-400">Last Contacted</dt>
                    <dd class="mt-1 text-sm text-white">{{ $lead->last_contacted_at ? $lead->last_contacted_at->format('M d, Y h:i A') : '-' }}</dd>
                </div>
            </dl>
        </div>

        <!-- Fintech / B2B Project Requirements -->
        @if($lead->metadata && (isset($lead->metadata['job_title']) || isset($lead->metadata['solution_interest']) || isset($lead->metadata['project_stage'])))
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-sm p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Fintech Project Requirements</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if(isset($lead->metadata['job_title']))
                <div>
                    <dt class="text-sm font-medium text-gray-400">Job Title</dt>
                    <dd class="mt-1 text-sm text-white font-semibold">{{ $lead->metadata['job_title'] }}</dd>
                </div>
                @endif
                @if(isset($lead->metadata['solution_interest']))
                <div>
                    <dt class="text-sm font-medium text-gray-400">Solution Interest</dt>
                    <dd class="mt-1 text-sm text-brand-primary font-bold">{{ $lead->metadata['solution_interest'] }}</dd>
                </div>
                @endif
                @if(isset($lead->metadata['project_stage']))
                <div>
                    <dt class="text-sm font-medium text-gray-400">Project Stage</dt>
                    <dd class="mt-1 text-sm text-white font-semibold">{{ $lead->metadata['project_stage'] }}</dd>
                </div>
                @endif
                @if(isset($lead->metadata['clicked_cta']))
                <div>
                    <dt class="text-sm font-medium text-gray-400">Clicked CTA Source</dt>
                    <dd class="mt-1 text-sm text-white"><code class="bg-white/[0.06] px-2 py-0.5 rounded text-xs text-brand-primary font-semibold font-mono">{{ $lead->metadata['clicked_cta'] }}</code></dd>
                </div>
                @endif
            </dl>
        </div>
        @endif

        <!-- Form Resubmission History -->
        @if($lead->metadata && isset($lead->metadata['submission_history']) && count($lead->metadata['submission_history']) > 0)
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-sm p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Form Resubmission History</h2>
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach($lead->metadata['submission_history'] as $historyIndex => $history)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white text-white text-xs font-bold">
                                        {{ count($lead->metadata['submission_history']) - $historyIndex }}
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-white">
                                            Submitted interest in <span class="font-semibold text-brand-primary">{{ $history['solution_interest'] ?? 'N/A' }}</span> ({{ $history['project_stage'] ?? 'N/A' }})
                                            @if(!empty($history['notes']))
                                            <span class="block mt-1 text-xs text-gray-400 bg-white/[0.03] p-2.5 rounded border border-gray-150 whitespace-pre-wrap font-sans">"{{ $history['notes'] }}"</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right text-xs whitespace-nowrap text-gray-400">
                                        <time datetime="{{ $history['submitted_at'] ?? '' }}">{{ isset($history['submitted_at']) ? \Carbon\Carbon::parse($history['submitted_at'])->format('M d, Y h:i A') : 'N/A' }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <!-- Tracking Data -->
        @if($lead->utm_source || $lead->utm_medium || $lead->utm_campaign || $lead->utm_term || $lead->utm_content)
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-sm p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Tracking Data (UTM)</h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($lead->utm_source)
                <div>
                    <dt class="text-sm font-medium text-gray-400">UTM Source</dt>
                    <dd class="mt-1 text-sm text-white">{{ $lead->utm_source }}</dd>
                </div>
                @endif
                @if($lead->utm_medium)
                <div>
                    <dt class="text-sm font-medium text-gray-400">UTM Medium</dt>
                    <dd class="mt-1 text-sm text-white">{{ $lead->utm_medium }}</dd>
                </div>
                @endif
                @if($lead->utm_campaign)
                <div>
                    <dt class="text-sm font-medium text-gray-400">UTM Campaign</dt>
                    <dd class="mt-1 text-sm text-white">{{ $lead->utm_campaign }}</dd>
                </div>
                @endif
                @if($lead->utm_term)
                <div>
                    <dt class="text-sm font-medium text-gray-400">UTM Term</dt>
                    <dd class="mt-1 text-sm text-white">{{ $lead->utm_term }}</dd>
                </div>
                @endif
                @if($lead->utm_content)
                <div>
                    <dt class="text-sm font-medium text-gray-400">UTM Content</dt>
                    <dd class="mt-1 text-sm text-white">{{ $lead->utm_content }}</dd>
                </div>
                @endif
            </dl>
        </div>
        @endif

        <!-- Company Details -->
        @if($lead->company_details)
            <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-sm p-6 border-l-4 border-brand-primary">
                <h2 class="text-lg font-semibold text-white mb-4">Company Details & Research</h2>
                <p class="text-sm text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $lead->company_details }}</p>
            </div>
        @endif

        <!-- Notes -->
        @if($lead->notes)
            <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-sm p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Notes</h2>
                <p class="text-sm text-gray-300 whitespace-pre-wrap">{{ $lead->notes }}</p>
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
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-sm p-6">
            <h3 class="text-sm font-medium text-gray-400 mb-3">Status</h3>
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
                    <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 border border-white/10 rounded-lg text-sm bg-[#0a1612] text-white border-white/10">
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
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-sm p-6">
            <h3 class="text-sm font-medium text-gray-400 mb-3">Lead Score</h3>
            <div class="flex items-center justify-between mb-2">
                <span class="text-2xl font-bold text-white">{{ $lead->score }}</span>
                <span class="text-sm text-gray-400">/100</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-brand-primary h-2 rounded-full" style="width: {{ $lead->score }}%"></div>
            </div>
        </div>

        <!-- Assignment -->
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-sm p-6">
            <h3 class="text-sm font-medium text-gray-400 mb-3">Assigned To</h3>
            @if($lead->assignedTo)
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-brand-primary text-white flex items-center justify-center font-semibold">
                        {{ substr($lead->assignedTo->name, 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ $lead->assignedTo->name }}</p>
                        <p class="text-xs text-gray-400">{{ $lead->assignedTo->email }}</p>
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-400">Not assigned</p>
            @endif
        </div>

        <!-- Quick Actions -->
        @if($lead->status !== 'converted')
            <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-sm p-6">
                <h3 class="text-sm font-medium text-gray-400 mb-3">Quick Actions</h3>
                <div class="space-y-2">
                    <form action="{{ route('admin.leads.mark-contacted', $lead) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 text-sm text-left text-gray-300 hover:bg-white/[0.03] rounded-lg transition">
                            Mark as Contacted
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
