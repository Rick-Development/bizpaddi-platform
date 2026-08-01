@extends('admin::layouts.app')

@section('title', 'Edit Milestone: ' . $pm_milestone->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.pm-milestones.index') }}" class="text-sm font-medium text-gray-500 hover:text-brand-primary transition-colors flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Back to Milestones
                    </a>
                </li>
            </ol>
        </nav>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Milestone</h1>
                <p class="mt-2 text-sm text-gray-500 font-medium">
                    Modifying milestone details for <span class="text-brand-primary underline decoration-brand-primary/30">{{ $pm_milestone->project->name }}</span>
                </p>
            </div>
            <div class="hidden sm:block">
                 <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold border 
                    {{ $pm_milestone->status === 'completed' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-yellow-50 text-yellow-700 border-yellow-100' }}">
                    {{ ucfirst($pm_milestone->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.pm-milestones.update', $pm_milestone) }}" method="POST" class="p-6 sm:p-10 space-y-8">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-y-8 gap-x-6 sm:grid-cols-2">
                <!-- Project Selection -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2.5 ml-1">Associated Project</label>
                    <div class="relative">
                        <select name="project_id" required class="block w-full px-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/5 focus:border-brand-primary transition-all text-sm font-semibold appearance-none">
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ $pm_milestone->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Milestone Title -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2.5 ml-1">Milestone Title</label>
                    <input type="text" name="title" value="{{ old('title', $pm_milestone->title) }}" required 
                           class="block w-full px-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/5 focus:border-brand-primary transition-all text-sm font-semibold placeholder-gray-400"
                           placeholder="e.g., Final Design Sign-off">
                </div>

                <!-- Description -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2.5 ml-1">Detailed Description</label>
                    <textarea name="description" rows="5" 
                              class="block w-full px-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/5 focus:border-brand-primary transition-all text-sm font-semibold placeholder-gray-400"
                              placeholder="Describe the key criteria for this milestone...">{{ old('description', $pm_milestone->description) }}</textarea>
                </div>

                <!-- Due Date -->
                <div class="col-span-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2.5 ml-1 text-red-400">Deadline</label>
                    <div class="relative">
                        <input type="date" name="due_date" value="{{ old('due_date', $pm_milestone->due_date ? $pm_milestone->due_date->format('Y-m-d') : '') }}" 
                               class="block w-full px-4 py-3.5 bg-red-50/20 border border-red-100 rounded-2xl focus:ring-4 focus:ring-red-100 focus:border-red-400 transition-all text-sm font-bold text-red-600 appearance-none">
                    </div>
                </div>

                <!-- Status -->
                <div class="col-span-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2.5 ml-1">Current Status</label>
                    <div class="relative">
                        <select name="status" class="block w-full px-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/5 focus:border-brand-primary transition-all text-sm font-semibold appearance-none">
                            <option value="pending" {{ $pm_milestone->status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="completed" {{ $pm_milestone->status == 'completed' ? 'selected' : '' }}>✅ Completed</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Order -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2.5 ml-1">Sort Priority</label>
                    <input type="number" name="order" value="{{ old('order', $pm_milestone->order) }}" 
                           class="block w-full px-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-2xl focus:ring-4 focus:ring-brand-primary/5 focus:border-brand-primary transition-all text-sm font-semibold"
                           placeholder="0">
                    <p class="mt-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Lower numbers appear first in the project timeline.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-8 border-t border-gray-50">
                <button type="button" 
                        onclick="if(confirm('Are you sure you want to discard your changes?')) window.location='{{ route('admin.pm-milestones.index') }}'"
                        class="w-full sm:w-auto px-6 py-3 text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-widest">
                    Discard Changes
                </button>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <button type="submit" 
                            class="w-full sm:w-auto px-10 py-4 bg-brand-primary text-white rounded-2xl text-sm font-extrabold shadow-xl shadow-brand-primary/20 hover:bg-brand-primary/90 hover:-translate-y-1 transition-all active:scale-95 uppercase tracking-widest">
                        Save Milestone Details
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Help -->
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-blue-50/50 rounded-2xl p-4 border border-blue-100/50 flex items-start gap-3">
            <div class="h-8 w-8 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-blue-900 uppercase tracking-wider mb-1">Progress Tracking</p>
                <p class="text-xs text-blue-700 leading-relaxed font-medium">Milestone progress is automatically calculated based on the completion of the tasks linked to it.</p>
            </div>
        </div>
        <div class="bg-purple-50/50 rounded-2xl p-4 border border-purple-100/50 flex items-start gap-3">
            <div class="h-8 w-8 bg-purple-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-purple-900 uppercase tracking-wider mb-1">Deadline Alerts</p>
                <p class="text-xs text-purple-700 leading-relaxed font-medium">Setting a due date helps the team stay on schedule. Tasks within a late milestone will be highlighted.</p>
            </div>
        </div>
    </div>
</div>
@endsection
