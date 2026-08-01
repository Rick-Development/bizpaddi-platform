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
        <!-- Preview Placeholder -->
        <div class="h-48 bg-gray-100 flex items-center justify-center border-b border-gray-100">
            <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
        
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
