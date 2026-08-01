@extends('admin::layouts.app')

@section('title', 'Edit Script - ' . $script->title)

@section('content')
<div class="max-w-4xl mx-auto" x-data="scriptManager({{ $script->id }}, {{ json_encode($script->steps) }})">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <nav class="flex mb-1" aria-label="Breadcrumb">
                <ol role="list" class="flex items-center space-x-2">
                    <li><a href="{{ route('admin.communication-scripts.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Scripts</a></li>
                    <li>
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                        </svg>
                    </li>
                    <li><span class="text-sm font-medium text-gray-900" aria-current="page">Edit Script</span></li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Manage Script Steps</h1>
        </div>
        <div class="flex items-center gap-2">
            @php
                try {
                    $showUrl = route('admin.communication-scripts.show', ['communication_script' => $script->id]);
                } catch (\Exception $e) {
                    $showUrl = '#'; // Fallback
                    \Illuminate\Support\Facades\Log::error('Route error: ' . $e->getMessage(), ['script' => $script]);
                }
            @endphp
            <a href="{{ $showUrl }}" target="_blank" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                View Playbook
            </a>
            <button @click="isEditingScript = true" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2">
                Settings
            </button>
        </div>
    </div>

    <!-- Steps List -->
    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Script Steps</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Define the flow of conversation.</p>
            </div>
            <button @click="openStepModal()" class="inline-flex items-center rounded-md border border-transparent bg-brand-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Step
            </button>
        </div>
        
        <ul role="list" class="divide-y divide-gray-200">
            <template x-for="(step, index) in steps" :key="step.id">
                <li class="relative hover:bg-gray-50 transition-colors">
                    <div class="px-4 py-4 sm:px-6 flex items-start justify-between">
                        <div class="flex items-start gap-4 flex-1">
                            <!-- Drag Handle / Order -->
                            <div class="flex flex-col items-center gap-1 pt-1">
                                <span class="flex-shrink-0 h-6 w-6 rounded-full bg-gray-100 text-xs font-bold text-gray-600 flex items-center justify-center border border-gray-300" x-text="index + 1"></span>
                                <div class="flex flex-col gap-0.5">
                                    <button @click="moveStep(index, -1)" class="text-gray-400 hover:text-gray-600 disabled:opacity-30" :disabled="index === 0">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </button>
                                    <button @click="moveStep(index, 1)" class="text-gray-400 hover:text-gray-600 disabled:opacity-30" :disabled="index === steps.length - 1">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-brand-primary truncate" x-text="step.name"></h4>
                                <p class="mt-1 text-sm text-gray-600 line-clamp-2" x-text="step.content"></p>
                                
                                <!-- Meta/Exceptions Badge -->
                                <div class="mt-2 flex items-center gap-2">
                                    <template x-if="step.exceptions && step.exceptions.length > 0">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <span x-text="step.exceptions.length + ' Objections'"></span>
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="ml-4 flex items-center gap-2">
                            <button @click="editStep(step)" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</button>
                            <span class="text-gray-300">|</span>
                            <button @click="deleteStep(step.id)" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                        </div>
                    </div>
                </li>
            </template>
            
            <template x-if="steps.length === 0">
                 <li class="px-4 py-8 text-center text-gray-500 italic">
                    No steps added yet. Click "Add Step" to begin.
                </li>
            </template>
        </ul>
    </div>

    <!-- Step Edit/Create Modal - Redesigned -->
    <div x-show="isStepModalOpen" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gradient-to-br from-gray-900/80 to-gray-900/60 backdrop-blur-sm transition-opacity" 
                 aria-hidden="true" 
                 @click="isStepModalOpen = false"></div>

            <!-- Modal Panel -->
            <div class="relative inline-block w-full max-w-3xl text-left align-middle transition-all transform"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <!-- Header -->
                    <div class="relative px-6 py-5 bg-gradient-to-r from-brand-primary to-brand-primary/80">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white" x-text="editingStep ? 'Edit Step' : 'Create New Step'"></h3>
                                    <p class="text-sm text-white/80 mt-0.5">Configure script step details</p>
                                </div>
                            </div>
                            <button @click="isStepModalOpen = false" 
                                    class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 transition-colors flex items-center justify-center group">
                                <svg class="w-5 h-5 text-white/80 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="px-6 py-6 space-y-6 max-h-[calc(100vh-16rem)] overflow-y-auto">
                        <!-- Step Name -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                Step Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="stepForm.name" 
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition-all duration-200 text-gray-900 placeholder-gray-400" 
                                   placeholder="e.g. Introduction, Discovery Questions, Closing">
                        </div>
                        
                        <!-- Script Content -->
                        <div class="group">
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                Script Content <span class="text-red-500">*</span>
                            </label>
                            <textarea x-model="stepForm.content" 
                                      rows="6" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-brand-primary focus:ring-4 focus:ring-brand-primary/10 transition-all duration-200 text-gray-900 placeholder-gray-400 resize-none" 
                                      placeholder="Enter the script content that the agent should follow..."></textarea>
                            <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Write clear, conversational script that guides the conversation naturally
                            </p>
                        </div>

                        <!-- Objection Handling Section -->
                        <div class="border-t-2 border-gray-100 pt-6">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900">
                                        Objection Handling
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1">Define responses to common objections</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-gray-100 text-xs font-medium text-gray-600" x-text="stepForm.exceptions.length + ' rules'"></span>
                            </div>
                            
                            <!-- Exception List -->
                            <div class="space-y-3 mb-4">
                                <template x-for="(exception, idx) in stepForm.exceptions" :key="idx">
                                    <div class="relative group/exception">
                                        <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl p-4 border-2 border-gray-200 hover:border-brand-primary/30 transition-all duration-200">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-primary/10 text-brand-primary flex items-center justify-center text-xs font-bold mt-1" x-text="idx + 1"></div>
                                                <div class="flex-1 space-y-3">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Trigger Phrase</label>
                                                        <input type="text" 
                                                               x-model="exception.trigger_text" 
                                                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/10 transition-all" 
                                                               placeholder='e.g. "I\'m not interested" or "It\'s too expensive"'>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Response</label>
                                                        <textarea x-model="exception.response_content" 
                                                                  rows="3" 
                                                                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/10 transition-all resize-none" 
                                                                  placeholder="How should the agent respond to this objection?"></textarea>
                                                    </div>
                                                </div>
                                                <button @click="removeExceptionFromForm(idx)" 
                                                        class="flex-shrink-0 w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 opacity-0 group-hover/exception:opacity-100 transition-all duration-200 flex items-center justify-center">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            <!-- Add Exception Button -->
                            <button @click="addExceptionToForm()" 
                                    class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl hover:border-brand-primary hover:bg-brand-primary/5 transition-all duration-200 flex items-center justify-center gap-2 text-sm font-medium text-gray-600 hover:text-brand-primary group">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Objection Rule
                            </button>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                        <button type="button" 
                                @click="isStepModalOpen = false" 
                                class="px-5 py-2.5 rounded-xl border-2 border-gray-300 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-200">
                            Cancel
                        </button>
                        <button type="button" 
                                @click="saveStep()" 
                                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-brand-primary to-brand-primary/90 text-sm font-semibold text-white hover:shadow-lg hover:shadow-brand-primary/25 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-brand-primary/20 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span x-text="editingStep ? 'Update Step' : 'Create Step'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Settings Modal - Redesigned -->
    <div x-show="isEditingScript" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gradient-to-br from-gray-900/80 to-gray-900/60 backdrop-blur-sm transition-opacity" 
                 aria-hidden="true" 
                 @click="isEditingScript = false"></div>

            <!-- Modal Panel -->
            <div class="relative inline-block w-full max-w-2xl text-left align-middle transition-all transform"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <form action="{{ route('admin.communication-scripts.update', $script->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                        <!-- Header -->
                        <div class="relative px-6 py-5 bg-gradient-to-r from-indigo-600 to-indigo-500">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">Script Settings</h3>
                                        <p class="text-sm text-white/80 mt-0.5">Configure script properties</p>
                                    </div>
                                </div>
                                <button type="button"
                                        @click="isEditingScript = false" 
                                        class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 transition-colors flex items-center justify-center group">
                                    <svg class="w-5 h-5 text-white/80 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="px-6 py-6 space-y-5">
                            <!-- Title Field -->
                            <div class="group">
                                <label for="title" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="title" 
                                       id="title" 
                                       value="{{ $script->title }}" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 text-gray-900 placeholder-gray-400" 
                                       placeholder="e.g. Sales Discovery Call"
                                       required>
                            </div>
                            
                            <!-- Description Field -->
                            <div class="group">
                                <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Description
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="4" 
                                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 text-gray-900 placeholder-gray-400 resize-none" 
                                          placeholder="Brief description of when and how to use this script...">{{ $script->description }}</textarea>
                                <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Optional: Provide context for when this script should be used
                                </p>
                            </div>
                            
                            <!-- Active Toggle -->
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl p-4 border-2 border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-white border border-gray-300 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <label for="is_active" class="block text-sm font-semibold text-gray-900">
                                                Active Status
                                            </label>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                Enable this script for use in conversations
                                            </p>
                                        </div>
                                    </div>
                                    <div class="relative inline-flex items-center">
                                        <input id="is_active" 
                                               name="is_active" 
                                               type="checkbox" 
                                               value="1" 
                                               {{ $script->is_active ? 'checked' : '' }} 
                                               class="sr-only peer">
                                        <label for="is_active" 
                                               class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 cursor-pointer"></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                            <button type="button" 
                                    @click="isEditingScript = false" 
                                    class="px-5 py-2.5 rounded-xl border-2 border-gray-300 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-gray-200">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-500 text-sm font-semibold text-white hover:shadow-lg hover:shadow-indigo-500/25 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Update Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('scriptManager', (scriptId, initialSteps) => ({
            scriptId: scriptId,
            steps: initialSteps || [],
            isStepModalOpen: false,
            isEditingScript: false,
            editingStep: null,
            stepForm: {
                name: '',
                content: '',
                exceptions: []
            },

            openStepModal(step = null) {
                this.editingStep = step;
                if (step) {
                    this.stepForm = {
                        id: step.id,
                        name: step.name,
                        content: step.content,
                        exceptions: step.exceptions ? JSON.parse(JSON.stringify(step.exceptions)) : []
                    };
                } else {
                    this.stepForm = {
                        name: '',
                        content: '',
                        exceptions: []
                    };
                }
                this.isStepModalOpen = true;
            },

            editStep(step) {
                this.openStepModal(step);
            },

            addExceptionToForm() {
                this.stepForm.exceptions.push({
                    trigger_text: '',
                    response_content: '',
                    action: null
                });
            },

            removeExceptionFromForm(index) {
                this.stepForm.exceptions.splice(index, 1);
            },

            saveStep() {
                if (!this.stepForm.name || !this.stepForm.content) {
                    alert('Please fill in Step Name and Content.');
                    return;
                }

                const url = this.editingStep 
                    ? '{{ url("admin/communication-script-steps") }}/' + this.editingStep.id 
                    : '{{ route("admin.communication-script-steps.store") }}';
                
                const method = this.editingStep ? 'PUT' : 'POST';
                const body = {
                    script_id: this.scriptId,
                    name: this.stepForm.name,
                    content: this.stepForm.content,
                    order: this.editingStep ? this.editingStep.order : (this.steps.length + 1),
                    exceptions: this.stepForm.exceptions
                };

                fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(body)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                        this.isStepModalOpen = false;
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(err => console.error(err));
            },

            deleteStep(stepId) {
                if (!confirm('Delete this step?')) return;
                fetch('{{ url("admin/communication-script-steps") }}/' + stepId, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.steps = this.steps.filter(s => s.id !== stepId);
                    }
                });
            },

            moveStep(index, direction) {
                if (index + direction < 0 || index + direction >= this.steps.length) return;
                
                // Swap in array
                const temp = this.steps[index];
                this.steps[index] = this.steps[index + direction];
                this.steps[index + direction] = temp;

                // Send reorder request
                const orderedIds = this.steps.map(s => s.id);
                fetch('{{ route("admin.communication-script-steps.reorder") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ steps: orderedIds })
                });
            }
        }));
    });
</script>
@endsection