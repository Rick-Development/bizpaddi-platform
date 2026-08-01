@extends('admin::layouts.app')

@section('title', 'Playbook - ' . $script->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.communication-scripts.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">{{ $script->title }}</h1>
                    {{ $script->channel->name ?? 'No Channel' }}
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-500 ml-7">{{ $script->description }}</p>
        </div>
        <div class="flex gap-2">
            @php
                try {
                    $editUrl = route('admin.communication-scripts.edit', ['communication_script' => $script->id]);
                } catch (\Exception $e) {
                    $editUrl = '#'; 
                    \Illuminate\Support\Facades\Log::error('View/Edit Route Error: ' . $e->getMessage(), ['id' => $script->id ?? 'null', 'script' => $script]);
                }
            @endphp
            <a href="{{ $editUrl }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Steps
            </a>
        </div>
    </div>

    <!-- Steps List -->
    <div class="space-y-6 pb-20" x-data="{ 
        copiedStep: null,
        copyToClipboard(text, stepId) {
            // Try Clipboard API first (works in secure contexts)
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    this.copiedStep = stepId;
                    setTimeout(() => this.copiedStep = null, 2000);
                }).catch(err => {
                    console.warn('Clipboard API failed, trying fallback', err);
                    this.fallbackCopy(text, stepId);
                });
            } else {
                // Fallback for insecure contexts (e.g. HTTP local IP)
                this.fallbackCopy(text, stepId);
            }
        },
        fallbackCopy(text, stepId) {
            let textArea = document.createElement('textarea');
            textArea.value = text;
            
            // Ensure it's not visible but part of DOM
            textArea.style.position = 'fixed';
            textArea.style.left = '-9999px';
            textArea.style.top = '0';
            document.body.appendChild(textArea);
            
            textArea.focus();
            textArea.select();
            
            try {
                var successful = document.execCommand('copy');
                if (successful) {
                    this.copiedStep = stepId;
                    setTimeout(() => this.copiedStep = null, 2000);
                } else {
                    console.error('Fallback copy failed');
                }
            } catch (err) {
                console.error('Fallback copy error', err);
            }
            
            document.body.removeChild(textArea);
        }
    }">
        @forelse($script->steps as $index => $step)
            <div class="bg-white shadow sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-gray-200 text-xs font-bold text-gray-600 flex items-center justify-center border border-gray-300">
                            {{ $step->order }}
                        </span>
                        {{ $step->name }}
                    </h3>
                    <button 
                        @click="copyToClipboard($refs.stepContent{{ $step->id }}.innerText.trim(), {{ $step->id }})"
                        class="text-xs font-medium text-brand-primary hover:text-brand-primary/80 flex items-center gap-1 transition-colors"
                        :class="{'text-green-600': copiedStep === {{ $step->id }}}"
                    >
                        <svg x-show="copiedStep !== {{ $step->id }}" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                        </svg>
                        <svg x-show="copiedStep === {{ $step->id }}" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span x-text="copiedStep === {{ $step->id }} ? 'Copied!' : 'Copy Script'"></span>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="prose max-w-none text-gray-800">
                        <p x-ref="stepContent{{ $step->id }}" class="whitespace-pre-wrap font-medium text-lg leading-relaxed">{{ $step->content }}</p>
                    </div>

                    @if($step->key_points && count($step->key_points) > 0)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Key Points</h4>
                            <ul class="list-disc pl-5 text-sm text-gray-600 space-y-1">
                                @foreach($step->key_points as $point)
                                    <li>{{ $point }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($step->exceptions->count() > 0)
                        <div class="mt-4 pt-4 border-t border-gray-100" x-data="{ showExceptions: false }">
                            <button @click="showExceptions = !showExceptions" class="text-xs font-semibold text-red-600 flex items-center gap-1 hover:text-red-700">
                                <svg class="w-3 h-3" :class="{'rotate-180': showExceptions}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                Objection Handling ({{ $step->exceptions->count() }})
                            </button>
                            
                            <div x-show="showExceptions" class="mt-3 grid gap-3" style="display: none;">
                                @foreach($step->exceptions as $exception)
                                    <div class="bg-red-50 rounded-md p-3 border border-red-100">
                                        <p class="text-xs font-bold text-red-800 mb-1">If they say: "{{ $exception->trigger_text }}"</p>
                                        <p class="text-sm text-gray-800">{{ $exception->response_content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-lg shadow border border-gray-200">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No steps defined</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating the first step of this playbook.</p>
                <div class="mt-6">
                    @php
                        try {
                            $createStepUrl = route('admin.communication-scripts.edit', ['communication_script' => $script->id]);
                        } catch (\Exception $e) {
                             $createStepUrl = '#'; 
                        }
                    @endphp
                    <a href="{{ $createStepUrl }}" class="inline-flex items-center rounded-md border border-transparent bg-brand-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2">
                        Create First Step
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
