@extends('admin::layouts.app')

@section('title', 'Task Assignment Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-slate-100 pb-6">
        <div>
            <a href="{{ route('admin.aiemployees.tasks.index') }}" class="inline-flex items-center text-sm font-semibold text-brand-primary hover:text-brand-primary/80 transition-colors mb-2">
                <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                Back to Assignments
            </a>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Task Details</h1>
            <p class="mt-1.5 text-sm text-slate-500">Track real-time progress, outputs, and system actions for task #{{ $task->id }}.</p>
        </div>
        <div class="flex items-center gap-3">
            @if($task->status === 'failed')
                <form action="{{ route('admin.aiemployees.tasks.retry', $task) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 px-5 py-2.5 text-sm font-bold transition-all shadow-sm">Retry Task</button>
                </form>
            @endif
            
            <a href="{{ route('admin.aiemployees.tasks.edit', $task) }}" class="rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 px-5 py-2.5 text-sm font-bold transition-all shadow-sm {{ $task->status === 'processing' ? 'pointer-events-none opacity-50' : '' }}">Edit Task</a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Side: Task Mandate and Response Output (2 cols on large screen) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Mandate Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                    <h2 class="text-md font-bold text-slate-800">Task Mandate & Details</h2>
                </div>
                <div class="p-6">
                    <p class="text-slate-700 text-base leading-relaxed whitespace-pre-line">{{ $task->task }}</p>
                </div>
            </div>

            <!-- AI Output Result -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-md font-bold text-slate-800">AI Response Output</h2>
                    @if($task->status === 'completed' && $task->output)
                        <button type="button" class="btn-copy text-xs font-bold text-brand-primary hover:underline transition-all flex items-center gap-1" data-clipboard-target="#rawOutputText">
                            📋 Copy Response
                        </button>
                    @endif
                </div>
                <div class="p-6">
                    @if($task->status === 'completed' && $task->output)
                        @php
                            $out = json_decode($task->output, true);
                            $resultText = $out['result'] ?? ($out['text'] ?? $task->output);
                            
                            $parsedHtml = e($resultText);
                            $parsedHtml = preg_replace('/\*\*(.*?)\*\*/', '<strong class="font-extrabold text-slate-900">$1</strong>', $parsedHtml);
                            $parsedHtml = preg_replace('/^\s*-\s+(.*?)$/m', '<li class="ml-4 list-disc text-slate-700">$1</li>', $parsedHtml);
                            $parsedHtml = preg_replace('/^\s*\d+\.\s+(.*?)$/m', '<h4 class="font-bold text-slate-900 mt-4 mb-1">$1</h4>', $parsedHtml);
                            $parsedHtml = nl2br($parsedHtml);
                        @endphp

                        <div class="prose max-w-none text-slate-800 text-sm leading-relaxed" id="formattedOutput">
                            {!! $parsedHtml !!}
                        </div>
                        <textarea class="hidden" id="rawOutputText">{{ $resultText }}</textarea>
                    @elseif($task->status === 'failed' && $task->output)
                        @php $out = json_decode($task->output, true); @endphp
                        <div class="rounded-xl bg-red-50 border border-red-100 p-5 flex items-start gap-3">
                            <span class="text-2xl mt-0.5">⚠️</span>
                            <div>
                                <h4 class="font-bold text-red-800 text-sm">Execution Error Detected</h4>
                                <p class="text-red-700 text-sm mt-1 leading-relaxed">{{ $out['text'] ?? 'The AI agent failed to execute the task command details.' }}</p>
                            </div>
                        </div>
                    @elseif($task->status === 'processing')
                        <div class="py-12 text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-slate-200 border-t-brand-primary mb-3"></div>
                            <p class="font-bold text-slate-700 text-sm">AI Agent Working...</p>
                            <p class="text-slate-400 text-xs mt-1">Please wait while the orchestrator finishes reasoning and tool calls.</p>
                        </div>
                    @else
                        <div class="py-12 text-center text-slate-400">
                            <div class="text-4xl mb-3">⏳</div>
                            <p class="font-bold text-slate-600 text-sm">Task Pending Execution</p>
                            <p class="text-slate-400 text-xs mt-1">This task is currently waiting in the Laravel queue process.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Side: Employee Meta, Prompts, and Leads (1 col on large screen) -->
        <div class="space-y-6">
            <!-- Employee and Status Box -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm space-y-6">
                <!-- AI Employee Profile -->
                <div class="flex items-center gap-4 pb-5 border-b border-slate-100">
                    <div class="h-12 w-12 rounded-xl bg-brand-primary/10 border border-brand-primary/20 flex items-center justify-center text-brand-primary text-lg font-bold flex-shrink-0">
                        {{ strtoupper(substr($task->employee->name ?? 'AI', 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Assigned Agent</h3>
                        <p class="text-base font-extrabold text-slate-800 mt-0.5">{{ $task->employee->name ?? 'Unassigned' }}</p>
                        <span class="inline-block bg-slate-100 text-slate-600 text-xxs font-bold uppercase tracking-wider px-2 py-0.5 rounded-md mt-1">
                            {{ str_replace('_', ' ', Str::title($task->employee->role ?? '')) }}
                        </span>
                    </div>
                </div>

                <!-- Execution Status Badge -->
                <div class="flex justify-between items-center py-1">
                    <span class="text-sm font-bold text-slate-400">Status</span>
                    @php
                        $statusColors = [
                            'pending'    => 'background: #fef3c7; color: #d97706; border: 1px solid #fde68a;',
                            'processing' => 'background: #dbeafe; color: #2563eb; border: 1px solid #bfdbfe;',
                            'completed'  => 'background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0;',
                            'failed'     => 'background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca;',
                        ];
                    @endphp
                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold uppercase tracking-wider" style="{{ $statusColors[$task->status] ?? '' }}">
                        {{ $task->status }}
                    </span>
                </div>

                <!-- Timestamps -->
                <div class="space-y-3 pt-4 border-t border-slate-100 text-xs text-slate-500 font-medium">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Assigned:</span>
                        <span>{{ $task->created_at->format('M d, Y H:i') }} ({{ $task->created_at->diffForHumans() }})</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Last Action:</span>
                        <span>{{ $task->updated_at->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- AI Agent System Instructions (Collapsible/Preview) -->
            @if($task->employee->system_prompt ?? null)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="border-b border-slate-100 bg-slate-50 px-6 py-4">
                        <h2 class="text-sm font-bold text-slate-800">Agent Persona Prompt</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-slate-600 text-xs leading-relaxed italic bg-slate-50 border border-slate-100 rounded-xl p-4 whitespace-pre-line">{{ Str::limit($task->employee->system_prompt, 300) }}</p>
                    </div>
                </div>
            @endif

            <!-- Generated Leads (Only if any exist for this BDM) -->
            @if(!empty($leads) && count($leads) > 0)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="border-b border-slate-100 bg-slate-50 px-6 py-4 flex items-center justify-between">
                        <h2 class="text-sm font-bold text-slate-800">CRM Leads Generated</h2>
                        <span class="bg-brand-primary/10 text-brand-primary text-xs font-bold px-2 py-0.5 rounded-full">{{ count($leads) }}</span>
                    </div>
                    <div class="p-6 divide-y divide-slate-100">
                        @foreach($leads as $lead)
                            <div class="py-3 first:pt-0 last:pb-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-bold text-slate-800 text-sm">{{ $lead->name }}</h4>
                                    <span class="inline-block bg-slate-100 text-slate-600 text-xxs font-bold px-1.5 py-0.5 rounded uppercase tracking-wider">{{ $lead->status }}</span>
                                </div>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $lead->company }}</p>
                                @if($lead->value)
                                    <p class="text-xs text-emerald-600 font-semibold mt-1">Est. Value: ${{ number_format($lead->value) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelector('.btn-copy')?.addEventListener('click', function() {
        const copyText = document.getElementById('rawOutputText');
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value).then(() => {
            const originalText = this.innerHTML;
            this.innerHTML = '✅ Copied!';
            setTimeout(() => {
                this.innerHTML = originalText;
            }, 2000);
        });
    });
</script>
@endpush
