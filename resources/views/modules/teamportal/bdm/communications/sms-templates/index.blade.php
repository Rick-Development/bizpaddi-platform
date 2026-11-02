@extends('teamportal::layouts.app')

@section('title', 'SMS Templates')

@section('content')
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">SMS Templates</h1>
            <p class="mt-2 text-sm text-gray-600">Manage your reusable SMS message templates</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('team.bdm.sms-templates.create') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-[#0A4335] hover:to-[#0E5E4A] shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                New SMS Template
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($templates as $template)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 overflow-hidden group">
        <!-- Icon -->
        <div class="h-24 bg-gradient-to-br from-[#0E5E4A]/5 to-[#0A4335]/10 flex items-center justify-center border-b border-gray-100">
            <svg class="h-10 w-10 text-emerald-400/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
            </svg>
        </div>

        <div class="p-4">
            <div class="flex justify-between items-start">
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-emerald-400 transition-colors truncate">{{ $template->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $template->content }}</p>
                </div>
                <div class="flex space-x-2 ml-2 flex-shrink-0">
                    <a href="{{ route('team.bdm.sms-templates.edit', $template) }}" class="text-gray-400 hover:text-gray-600" title="Edit">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="{{ route('team.bdm.sms-templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Delete this template?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-500" title="Delete">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                <span>{{ $template->content ? mb_strlen(strip_tags($template->content)) : 0 }} chars</span>
                <div class="flex items-center gap-2">
                    <span>Updated {{ $template->updated_at->diffForHumans() }}</span>
                    @if($template->is_active)
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">Active</span>
                    @else
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-16 text-center bg-white rounded-xl border border-dashed border-gray-300">
        <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
        </svg>
        <p class="text-gray-500">No SMS templates yet.</p>
        <a href="{{ route('team.bdm.sms-templates.create') }}" class="mt-3 inline-flex items-center text-sm text-emerald-400 hover:underline font-medium">
            Create your first template &rarr;
        </a>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $templates->links() }}
</div>
@endsection
