@extends('admin::layouts.app')

@section('title', 'MCP Automation Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 -m-6 p-6">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">MCP Automation Center</h1>
                <p class="mt-1 text-sm text-gray-500">Manage Model Context Protocol tools and AI integrations</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                    <span class="mr-1.5 flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    System Operational
                </span>
            </div>
        </div>

        <!-- AI Generator CTA -->
        <a href="{{ route('admin.mcp.ai-generator') }}" class="group block relative overflow-hidden rounded-2xl bg-brand-primary p-8 shadow-lg hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-24 h-24 bg-black/10 rounded-full blur-xl"></div>
            
            <div class="relative flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-5">
                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />
                        </svg>
                    </div>
                    <div class="text-center sm:text-left">
                        <h2 class="text-2xl font-bold text-white tracking-tight">AI Content Generator</h2>
                        <p class="text-indigo-100 mt-1 text-sm font-medium">Create blogs, assign tasks, and generate quotes automatically ✨</p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center gap-2 rounded-xl bg-white/95 px-5 py-2.5 text-sm font-bold text-brand-primary shadow-sm group-hover:bg-white transition-colors">
                        Launch AI
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </span>
                </div>
            </div>
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Available Tools -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-100 px-6 py-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Available Tools</h3>
                            <p class="mt-1 text-xs text-gray-500">Exposed capabilities for AI Agents</p>
                        </div>
                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                            {{ count($tools) }} Tools Active
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 divide-y divide-gray-100">
                        @foreach($tools as $tool)
                        <div class="px-6 py-4 hover:bg-gray-50/50 transition-colors group">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <h4 class="text-sm font-semibold text-gray-900">{{ $tool['name'] }}</h4>
                                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                            {{ explode('_', $tool['name'])[0] }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $tool['description'] }}</p>
                                    
                                    <div class="mt-3 flex items-center gap-2 text-xs text-gray-400 font-mono">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                        {{ implode(', ', array_keys($tool['inputSchema']['properties'])) }}
                                    </div>
                                </div>
                                <div class="flex-shrink-0 pt-0.5">
                                    <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 group-hover:bg-white group-hover:shadow-sm transition-all">
                                        {{ count($tool['inputSchema']['properties']) }} params
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Info & Actions -->
            <div class="space-y-6">
                <!-- Connection Info -->
                <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-800 overflow-hidden text-white">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-indigo-500/20 rounded-lg">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-white">Connect External AI</h3>
                        </div>
                        
                        <p class="text-sm text-gray-400 mb-4">Use this endpoint to connect Cursor, Claude, or other MCP clients.</p>
                        
                        <div class="space-y-4">
                            <div class="bg-gray-950/50 rounded-lg p-3 border border-gray-800">
                                <span class="text-[10px] font-bold tracking-wider text-gray-500 uppercase block mb-1">Server URL</span>
                                <code class="text-xs sm:text-sm font-mono text-emerald-400 break-all select-all">{{ route('mcp.handle') }}</code>
                            </div>
                            
                            <div>
                                <span class="text-[10px] font-bold tracking-wider text-gray-500 uppercase block mb-2">Capabilities</span>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center rounded-md bg-indigo-400/10 px-2 py-1 text-xs font-medium text-indigo-400 ring-1 ring-inset ring-indigo-400/30">Content</span>
                                    <span class="inline-flex items-center rounded-md bg-purple-400/10 px-2 py-1 text-xs font-medium text-purple-400 ring-1 ring-inset ring-purple-400/30">Tasks</span>
                                    <span class="inline-flex items-center rounded-md bg-amber-400/10 px-2 py-1 text-xs font-medium text-amber-400 ring-1 ring-inset ring-amber-400/30">Finance</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Recent Activity</h3>
                    <div class="text-center py-8 border-2 border-dashed border-gray-100 rounded-xl bg-gray-50/50">
                        <svg class="mx-auto h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-2 text-xs text-gray-500">No automated actions yet</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
