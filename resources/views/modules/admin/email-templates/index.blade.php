@extends('admin::layouts.app')

@section('title', 'Email Templates')

@section('content')
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Email Templates</h1>
            <p class="mt-2 text-sm text-gray-600">Design and manage your email templates</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.email-templates.create') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[#FB4C4B] to-[#D93837] hover:from-[#D93837] hover:to-[#FB4C4B] shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New Template
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($templates as $template)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden group">
        <!-- Preview Banner / Category Graphic -->
        @if($template->thumbnail)
            <div class="h-48 border-b border-gray-100 overflow-hidden relative group-hover:scale-105 transition-transform duration-300">
                <img src="{{ $template->thumbnail }}" alt="{{ $template->name }} Preview" class="w-full h-full object-cover">
                <span class="absolute bottom-3 right-4 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase bg-black/60 text-white backdrop-blur-sm">
                    {{ $template->category ?? 'System' }}
                </span>
            </div>
        @else
            @php
                $category = strtolower($template->category ?? 'system');
                if (str_contains($category, 'attendance')) {
                    $gradient = 'from-violet-600 to-indigo-600';
                    $icon = '<svg class="h-16 w-16 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>';
                } elseif (str_contains($category, 'task')) {
                    $gradient = 'from-rose-500 to-red-600';
                    $icon = '<svg class="h-16 w-16 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>';
                } elseif (str_contains($category, 'report') || str_contains($category, 'summary')) {
                    $gradient = 'from-emerald-500 to-teal-600';
                    $icon = '<svg class="h-16 w-16 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
                    </svg>';
                } elseif (str_contains($category, 'manager') || str_contains($category, 'pm')) {
                    $gradient = 'from-blue-600 to-cyan-600';
                    $icon = '<svg class="h-16 w-16 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>';
                } else {
                    $gradient = 'from-gray-600 to-slate-700';
                    $icon = '<svg class="h-16 w-16 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>';
                }
            @endphp
            <div class="h-48 bg-gradient-to-br {{ $gradient }} flex flex-col items-center justify-center p-6 text-center border-b border-gray-100 relative group-hover:scale-105 transition-transform duration-300">
                {!! $icon !!}
                <span class="absolute bottom-3 right-4 px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase bg-white/20 text-white backdrop-blur-sm">
                    {{ $template->category ?? 'System' }}
                </span>
            </div>
        @endif
        
        <div class="p-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-[#FB4C4B] transition-colors line-clamp-1">{{ $template->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $template->category ?? 'Uncategorized' }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.email-templates.edit', $template) }}" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                <span>Updated {{ $template->updated_at->diffForHumans() }}</span>
                @if($template->is_active)
                    <span class="text-green-600 font-medium">Active</span>
                @else
                    <span class="text-gray-400">Inactive</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 text-center text-gray-500 bg-white rounded-xl border border-dashed border-gray-300">
        No templates found. Create your first email template.
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $templates->links() }}
</div>
@endsection
