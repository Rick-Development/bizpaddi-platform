@extends('teamportal::layouts.app')

@section('title', 'Submit Daily Report')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Submit New Report</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">Log your daily progress, plans, and challenges.</p>
        </div>
        <a href="{{ route('team.reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs sm:text-sm font-semibold text-gray-300 hover:bg-white/10 hover:text-white transition-all shadow-sm">
            &larr; Back to Reports
        </a>
    </div>

    <form action="{{ route('team.reports.store') }}" method="POST" class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/10 p-6 sm:p-8 space-y-6">
        @csrf
        <div>
            <label for="hours_worked" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">Hours Worked</label>
            <input type="number" step="0.5" name="hours_worked" id="hours_worked" class="bg-[#08110e]/80 border border-white/10 rounded-xl text-white px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 w-full sm:w-48 shadow-inner" placeholder="e.g. 8.0" required>
        </div>

        <div>
            <label for="tasks_completed" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">What did you accomplish today? <span class="text-red-400">*</span></label>
            <textarea name="tasks_completed" id="tasks_completed" rows="4" class="w-full bg-[#08110e]/80 border border-white/10 rounded-xl text-white p-4 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 shadow-inner" placeholder="List key completed deliverables..." required></textarea>
        </div>

        <div>
            <label for="tasks_planned" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">What is the plan for tomorrow? <span class="text-red-400">*</span></label>
            <textarea name="tasks_planned" id="tasks_planned" rows="4" class="w-full bg-[#08110e]/80 border border-white/10 rounded-xl text-white p-4 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 shadow-inner" placeholder="Outline upcoming priorities..." required></textarea>
        </div>

        <div>
            <label for="challenges" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-2">Any blockers or challenges?</label>
            <textarea name="challenges" id="challenges" rows="3" class="w-full bg-[#08110e]/80 border border-white/10 rounded-xl text-white p-4 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-emerald-500 shadow-inner" placeholder="Optional notes for management attention..."></textarea>
        </div>

        <div class="flex items-center justify-end gap-x-4 pt-4 border-t border-white/10">
            <a href="{{ route('team.reports.index') }}" class="px-4 py-2.5 border border-white/10 rounded-xl text-xs font-semibold text-gray-300 hover:bg-white/5 transition-all">Cancel</a>
            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold shadow-md active:scale-95 transition-all">Submit Report</button>
        </div>
    </form>
</div>
@endsection
