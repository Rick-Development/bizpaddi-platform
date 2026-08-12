@extends('admin::layouts.app')

@section('title', 'AI Employee Training & Knowledge Base')

@section('content')
<!-- Header -->
<div class="relative overflow-hidden rounded-2xl bg-white px-6 py-8 shadow-sm border border-gray-200 sm:px-12 mb-8">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-brand-primary/5 via-white to-white"></div>
    <div class="relative flex flex-col sm:flex-row sm:items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Training & Knowledge Base</h1>
            <p class="mt-2 text-md text-gray-600">Train your AI workforce by uploading company guidelines, templates, FAQs, and domain knowledge.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.aiemployees.knowledge.create') }}" class="inline-flex items-center justify-center rounded-xl bg-brand-primary hover:bg-brand-primary/95 text-white px-5 py-3 text-sm font-semibold shadow-md shadow-brand-primary/20 transition-all">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Add Knowledge Document
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 mb-6 flex items-center shadow-sm">
        <svg class="h-5 w-5 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="ml-3 text-sm font-medium text-emerald-800">{{ session('success') }}</p>
    </div>
@endif

<!-- Filters / Search -->
<div class="bg-white rounded-2xl border border-slate-200 p-5 mb-8 shadow-sm">
    <form method="GET" action="{{ route('admin.aiemployees.knowledge.index') }}" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search documents by title, category, or content..." class="block w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:border-brand-primary focus:outline-none focus:ring-1 focus:ring-brand-primary bg-white">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="rounded-xl bg-slate-900 hover:bg-slate-800 text-white px-6 py-2.5 text-sm font-semibold transition-all">Search</button>
            @if(request('search'))
                <a href="{{ route('admin.aiemployees.knowledge.index') }}" class="rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2.5 text-sm font-semibold transition-all text-center">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Documents List -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Document Title</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Content Preview</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-wider">Date Added</th>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-wider pr-6">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($documents as $doc)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="h-9 w-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                    📄
                                </div>
                                <div class="text-sm font-bold text-slate-900">{{ $doc->title }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-700 px-2.5 py-1 text-xs font-bold uppercase tracking-wider">
                                {{ $doc->category }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm text-slate-600 line-clamp-1 max-w-md">
                                {{ strip_tags($doc->content) }}
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-sm text-slate-500 font-medium">
                            {{ $doc->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium pr-6">
                            <div class="inline-flex gap-2">
                                <a href="{{ route('admin.aiemployees.knowledge.edit', $doc) }}" class="rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 px-3 py-1.5 text-xs font-bold transition-all">Edit</a>
                                <button type="button" class="rounded-lg bg-red-50 hover:bg-red-100 text-red-600 border border-red-100 px-3 py-1.5 text-xs font-bold transition-all btn-delete-doc" data-id="{{ $doc->id }}">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="text-3xl mb-3">🎓</div>
                            <p class="text-sm font-semibold">No training documents uploaded yet.</p>
                            <p class="text-xs text-slate-400 mt-1">Upload files or input content to train your AI agents.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($documents->hasPages())
        <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
            {{ $documents->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('.btn-delete-doc').on('click', function() {
            const docId = $(this).data('id');
            const row = $(this).closest('tr');
            
            if (confirm('Are you sure you want to delete this knowledge document? This cannot be undone and the AI will lose access to this information.')) {
                $.ajax({
                    url: `/admin/aiemployees/knowledge/${docId}`,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            row.fadeOut(300, function() {
                                $(this).remove();
                            });
                        }
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Failed to delete document.');
                    }
                });
            }
        });
    });
</script>
@endpush
@endsection
