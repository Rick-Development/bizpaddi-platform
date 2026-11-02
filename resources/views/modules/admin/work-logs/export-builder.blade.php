@extends('admin::layouts.app')

@section('title', 'Custom Work Session & Project Report Generator')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.work-logs.index') }}" class="p-2 rounded-lg text-gray-400 hover:text-gray-400 hover:bg-white/[0.06] transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Custom Work Session & Task Performance Report Generator</h1>
            <p class="mt-1 text-sm text-gray-400">Configure parameters, scope, assigned projects, task metrics, and sections before exporting.</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-data="{ 
    period: 'custom',
    format: 'pdf',
    startDate: '{{ now()->startOfMonth()->format('Y-m-d') }}',
    endDate: '{{ now()->format('Y-m-d') }}',
    userId: '',
    includeKpis: true,
    includePerformance: true,
    includePlanned: true,
    includeAccomplishments: true,
    includeChallenges: true,

    setPeriod(p) {
        this.period = p;
        const today = new Date();
        const fmt = (d) => d.toISOString().split('T')[0];

        if (p === 'daily') {
            this.startDate = fmt(today);
            this.endDate = fmt(today);
        } else if (p === 'weekly') {
            const day = today.getDay();
            const mon = new Date(today); mon.setDate(today.getDate() - (day === 0 ? 6 : day - 1));
            const sun = new Date(mon); sun.setDate(mon.getDate() + 6);
            this.startDate = fmt(mon);
            this.endDate = fmt(sun);
        } else if (p === 'monthly') {
            const start = new Date(today.getFullYear(), today.getMonth(), 1);
            const end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            this.startDate = fmt(start);
            this.endDate = fmt(end);
        } else if (p === 'yearly') {
            this.startDate = fmt(new Date(today.getFullYear(), 0, 1));
            this.endDate = fmt(new Date(today.getFullYear(), 11, 31));
        }
        // For 'custom', keep whatever the user has set
    },

    get isPreset() {
        return this.period !== 'custom';
    },

    buildParams(extra) {
        let p = {
            period: this.period,
            start_date: this.startDate,
            end_date: this.endDate,
            user_id: this.userId,
            include_kpis: this.includeKpis ? 1 : 0,
            include_performance: this.includePerformance ? 1 : 0,
            include_planned: this.includePlanned ? 1 : 0,
            include_accomplishments: this.includeAccomplishments ? 1 : 0,
            include_challenges: this.includeChallenges ? 1 : 0,
            ...extra
        };
        return new URLSearchParams(p).toString();
    },

    get exportUrl() {
        let base = this.format === 'csv' ? '{{ route('admin.work-logs.export-csv') }}' : '{{ route('admin.work-logs.export-pdf') }}';
        return base + '?' + this.buildParams({});
    },

    get previewUrl() {
        return '{{ route('admin.work-logs.export-pdf') }}?' + this.buildParams({ preview: 1 });
    }
}">

    <!-- Generator Options Card -->
    <div class="lg:col-span-8 space-y-6">
        <div class="bg-[#10231C]/60 rounded-2xl p-6 border border-white/10 shadow-sm space-y-6">
            
            <!-- 1. Period Selector -->
            <div>
                <label class="block text-sm font-bold text-white mb-2">1. Select Report Period</label>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    <button type="button" @click="setPeriod('daily')" :class="period === 'daily' ? 'border-brand-primary bg-brand-primary/10 text-brand-primary font-bold' : 'border-white/10 bg-[#10231C]/60 text-gray-300 hover:bg-white/[0.03]'" class="p-3 border rounded-xl text-center text-xs transition-all">
                        📅 Daily
                    </button>
                    <button type="button" @click="setPeriod('weekly')" :class="period === 'weekly' ? 'border-brand-primary bg-brand-primary/10 text-brand-primary font-bold' : 'border-white/10 bg-[#10231C]/60 text-gray-300 hover:bg-white/[0.03]'" class="p-3 border rounded-xl text-center text-xs transition-all">
                        🗓️ Weekly
                    </button>
                    <button type="button" @click="setPeriod('monthly')" :class="period === 'monthly' ? 'border-brand-primary bg-brand-primary/10 text-brand-primary font-bold' : 'border-white/10 bg-[#10231C]/60 text-gray-300 hover:bg-white/[0.03]'" class="p-3 border rounded-xl text-center text-xs transition-all">
                        📆 Monthly
                    </button>
                    <button type="button" @click="setPeriod('yearly')" :class="period === 'yearly' ? 'border-brand-primary bg-brand-primary/10 text-brand-primary font-bold' : 'border-white/10 bg-[#10231C]/60 text-gray-300 hover:bg-white/[0.03]'" class="p-3 border rounded-xl text-center text-xs transition-all">
                        📊 Yearly
                    </button>
                    <button type="button" @click="setPeriod('custom')" :class="period === 'custom' ? 'border-brand-primary bg-brand-primary/10 text-brand-primary font-bold' : 'border-white/10 bg-[#10231C]/60 text-gray-300 hover:bg-white/[0.03]'" class="p-3 border rounded-xl text-center text-xs transition-all">
                        ⚙️ Custom Range
                    </button>
                </div>
            </div>

            <!-- Date Pickers (auto-filled for presets, editable for custom) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">
                        Start Date
                        <span x-show="isPreset" class="ml-1 text-brand-primary text-[10px] font-normal">(auto-set)</span>
                    </label>
                    <input type="date" x-model="startDate" :readonly="isPreset" :class="isPreset ? 'bg-white/[0.03] text-gray-400 cursor-not-allowed' : ''" class="w-full rounded-xl border-white/10 text-sm focus:ring-brand-primary focus:border-brand-primary bg-[#0a1612] text-white border-white/10">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-300 mb-1">
                        End Date
                        <span x-show="isPreset" class="ml-1 text-brand-primary text-[10px] font-normal">(auto-set)</span>
                    </label>
                    <input type="date" x-model="endDate" :readonly="isPreset" :class="isPreset ? 'bg-white/[0.03] text-gray-400 cursor-not-allowed' : ''" class="w-full rounded-xl border-white/10 text-sm focus:ring-brand-primary focus:border-brand-primary bg-[#0a1612] text-white border-white/10">
                </div>
            </div>

            <hr class="border-white/10">

            <!-- 2. Team Member Filter -->
            <div>
                <label class="block text-sm font-bold text-white mb-2">2. Target Audience / Team Scope</label>
                <select x-model="userId" class="w-full rounded-xl border-white/10 text-sm focus:ring-brand-primary focus:border-brand-primary p-3 bg-[#0a1612] text-white border-white/10">
                    <optgroup label="📋 Group Reports">
                        <option value="">All Team Members (General Corporate Report)</option>
                        <option value="group_active">✅ All Active Team Members Only</option>
                        <option value="group_suspended">🚫 All Suspended Team Members Only</option>
                        <option value="group_inactive">⏸️ All Inactive Team Members Only</option>
                    </optgroup>
                    <optgroup label="👤 Individual Members">
                        @foreach($allUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }}) — {{ ucfirst($user->status ?? 'active') }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <hr class="border-white/10">

            <!-- 3. Report Content Toggles -->
            <div>
                <label class="block text-sm font-bold text-white mb-3">3. Include Sections & Content</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 border rounded-xl border-white/10 hover:bg-white/[0.03] cursor-pointer">
                        <input type="checkbox" x-model="includeKpis" class="rounded text-brand-primary focus:ring-brand-primary h-4 w-4">
                        <span class="text-sm font-medium text-gray-300">Executive KPI Summary Cards</span>
                    </label>

                    <label class="flex items-center gap-3 p-3 border rounded-xl border-white/10 hover:bg-white/[0.03] cursor-pointer">
                        <input type="checkbox" x-model="includePerformance" class="rounded text-brand-primary focus:ring-brand-primary h-4 w-4">
                        <span class="text-sm font-medium text-gray-300">Task Performance, Workload & Assigned Projects</span>
                    </label>

                    <label class="flex items-center gap-3 p-3 border rounded-xl border-white/10 hover:bg-white/[0.03] cursor-pointer">
                        <input type="checkbox" x-model="includePlanned" class="rounded text-brand-primary focus:ring-brand-primary h-4 w-4">
                        <span class="text-sm font-medium text-gray-300">Planned Focus & Tasks</span>
                    </label>

                    <label class="flex items-center gap-3 p-3 border rounded-xl border-white/10 hover:bg-white/[0.03] cursor-pointer">
                        <input type="checkbox" x-model="includeAccomplishments" class="rounded text-brand-primary focus:ring-brand-primary h-4 w-4">
                        <span class="text-sm font-medium text-gray-300">Accomplishments & Outcomes</span>
                    </label>

                    <label class="flex items-center gap-3 p-3 border rounded-xl border-white/10 hover:bg-white/[0.03] cursor-pointer">
                        <input type="checkbox" x-model="includeChallenges" class="rounded text-brand-primary focus:ring-brand-primary h-4 w-4">
                        <span class="text-sm font-medium text-gray-300">Blockers & Challenges</span>
                    </label>
                </div>
            </div>

            <hr class="border-white/10">

            <!-- 4. Export Format -->
            <div>
                <label class="block text-sm font-bold text-white mb-2">4. Export Format</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="format" value="pdf" x-model="format" class="text-brand-primary focus:ring-brand-primary">
                        <span class="text-sm font-semibold text-white">PDF Document (.pdf)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="format" value="csv" x-model="format" class="text-brand-primary focus:ring-brand-primary">
                        <span class="text-sm font-semibold text-white">CSV Spreadsheet (.csv)</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary & Action Panel -->
    <div class="lg:col-span-4 space-y-6">
        <div class="bg-gradient-to-br from-brand-secondary to-gray-900 rounded-2xl p-6 text-white shadow-xl space-y-6">
            <h2 class="text-lg font-bold border-b border-white/10 pb-3">Report Summary</h2>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Period Type:</span>
                    <span class="font-bold capitalize" x-text="period"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Start Date:</span>
                    <span class="font-bold" x-text="startDate"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">End Date:</span>
                    <span class="font-bold" x-text="endDate"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Scope:</span>
                    <span class="font-bold" x-text="
                        userId === '' ? 'All Members (General)' :
                        userId === 'group_active' ? 'All Active Members' :
                        userId === 'group_suspended' ? 'All Suspended Members' :
                        userId === 'group_inactive' ? 'All Inactive Members' :
                        'Single Member'
                    "></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Output Format:</span>
                    <span class="font-bold uppercase text-brand-primary" x-text="format"></span>
                </div>
            </div>

            <div class="pt-4 space-y-3">
                <a :href="exportUrl" class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-brand-primary text-white font-bold rounded-xl hover:bg-brand-primary/90 transition-all shadow-lg text-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download Report
                </a>

                <template x-if="format === 'pdf'">
                    <a :href="previewUrl" target="_blank" class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-[#10231C]/60/10 text-white font-semibold rounded-xl hover:bg-[#10231C]/60/20 transition-all text-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.573 16.49 16.638 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Preview PDF in Browser
                    </a>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
