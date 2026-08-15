@extends('admin::layouts.app')

@section('title', 'Admin Comments Feed')

@push('styles')
<style>
    .timeline-container {
        position: relative;
    }
    .timeline-container::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 2rem;
        width: 2px;
        background-color: #f1f5f9;
    }
    .comment-card {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .comment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        border-color: #cbd5e1;
    }
</style>
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Admin Task Comments Feed</h1>
        <p class="text-sm text-gray-500 mt-1">Real-time global feed of comments posted across all tasks and projects. Click any card to inspect & reply.</p>
    </div>

    {{-- Comments Timeline Feed --}}
    <div class="timeline-container space-y-6" id="comments-timeline">
        @forelse($comments as $comment)
            @php
                $commentable = $comment->commentable;
                $taskUrl = '#';
                $title = 'Unknown Task';
                $parentName = '';

                if ($comment->commentable_type === \Modules\ProjectManagement\Models\Task::class) {
                    $title = $commentable ? $commentable->title : 'Deleted Task';
                    $parentName = ($commentable && $commentable->project) ? $commentable->project->name : 'No Project';
                    if ($commentable) {
                        $taskUrl = route('admin.tasks.show', $commentable->id);
                    }
                } elseif ($comment->commentable_type === \Modules\ProjectManagement\Models\Project::class) {
                    $title = $commentable ? $commentable->name : 'Deleted Project';
                    $parentName = 'Project';
                    if ($commentable) {
                        $taskUrl = route('admin.pm-projects.show', $commentable->id);
                    }
                }
            @endphp

            <div class="relative flex gap-6 comment-row group" id="comment-row-{{ $comment->id }}">
                {{-- Commenter Avatar Indicator --}}
                <div class="relative z-10 flex-shrink-0">
                    @if($comment->user && method_exists($comment->user, 'getFirstMediaUrl') && $comment->user->getFirstMediaUrl('avatar'))
                        <img src="{{ $comment->user->getFirstMediaUrl('avatar') }}" class="h-10 w-10 rounded-full object-cover border-4 border-white shadow-sm" alt="">
                    @else
                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold text-xs border-4 border-white shadow-sm" style="background: linear-gradient(135deg, #191C47, #FB4C4B);">
                            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 2)) }}
                        </div>
                    @endif
                </div>


                {{-- Comment Content Card (Clickable to navigate to task) --}}
                <a href="{{ $taskUrl }}" 
                   class="flex-1 bg-white rounded-2xl border border-gray-200/80 p-5 comment-card shadow-xs block no-underline group-hover:border-[#191C47]/40 transition-all">
                    
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-gray-900">{{ $comment->user->name ?? 'Unknown User' }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-gray-100 text-gray-600">
                                {{ str_contains($comment->user_type, 'Admin') ? 'Admin' : 'Team Member' }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-400 font-medium">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>

                    {{-- Target Task reference --}}
                    <div class="mb-3 flex items-center flex-wrap gap-1.5">
                        <span class="text-xs text-gray-500">commented on</span>
                        <span class="text-xs font-bold text-[#191C47] group-hover:underline">
                            {{ $title }}
                        </span>
                        @if($parentName)
                            <span class="text-[10px] text-gray-500 font-medium bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100">
                                {{ $parentName }}
                            </span>
                        @endif
                    </div>

                    {{-- Text Content --}}
                    <p class="text-xs md:text-sm text-gray-700 leading-relaxed font-normal mb-3">{{ $comment->content }}</p>

                    {{-- Footer Action Link --}}
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400 font-medium group-hover:text-[#191C47] transition-colors">
                        <span>Click to open task details & reply</span>
                        <span class="flex items-center gap-1 text-[#191C47] font-semibold text-xs">
                            View & Comment
                            <svg class="h-3.5 w-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </span>
                    </div>
                </a>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center" id="no-comments-msg">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900">No Comments Yet</h3>
                <p class="mt-1 text-xs text-gray-500">No comments have been posted across tasks or projects yet.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $comments->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (typeof window.Echo !== 'undefined' && window.Echo) {
            try {
                window.Echo.private('task-comments')
                    .listen('.Modules\\ProjectManagement\\Events\\CommentPosted', (e) => {
                        appendRealtimeComment(e);
                    });
            } catch(err) {
                console.error('Echo comments listener error:', err);
            }
        }
    });

    function appendRealtimeComment(e) {
        const noComments = document.getElementById('no-comments-msg');
        if (noComments) noComments.classList.add('hidden');

        let taskUrl = '#';
        if (e.commentable_type && e.commentable_type.includes('Task')) {
            taskUrl = `/admin/tasks/${e.commentable_id}`;
        } else {
            taskUrl = `/admin/pm-projects/${e.commentable_id}`;
        }

        const list = document.getElementById('comments-timeline');
        
        let avatarHtml = '';
        if (e.user_avatar) {
            avatarHtml = `<img src="${e.user_avatar}" class="h-10 w-10 rounded-full object-cover border-4 border-white shadow-sm" alt="">`;
        } else {
            avatarHtml = `<div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold text-xs border-4 border-white shadow-sm" style="background: linear-gradient(135deg, #191C47, #FB4C4B);">${e.user_name.substring(0, 2).toUpperCase()}</div>`;
        }

        const roleLabel = e.user_type.includes('Admin') ? 'Admin' : 'Team Member';

        const html = `
            <div class="relative flex gap-6 comment-row opacity-0 transform -translate-y-2 transition-all duration-500 group" id="comment-row-${e.id}">
                <div class="relative z-10 flex-shrink-0">${avatarHtml}</div>
                <a href="${taskUrl}" class="flex-1 bg-white rounded-2xl border border-gray-200/80 p-5 comment-card shadow-xs block no-underline group-hover:border-[#191C47]/40 transition-all">
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-gray-900">${e.user_name}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-gray-100 text-gray-600">${roleLabel}</span>
                        </div>
                        <span class="text-xs text-gray-400 font-medium">${e.formatted_time}</span>
                    </div>
                    <div class="mb-3 flex items-center flex-wrap gap-1.5">
                        <span class="text-xs text-gray-500">commented on</span>
                        <span class="text-xs font-bold text-[#191C47] group-hover:underline">${e.task_title}</span>
                    </div>
                    <p class="text-xs md:text-sm text-gray-700 leading-relaxed font-normal mb-3">${e.content}</p>
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400 font-medium group-hover:text-[#191C47] transition-colors">
                        <span>Click to open task details & reply</span>
                        <span class="flex items-center gap-1 text-[#191C47] font-semibold text-xs">
                            View & Comment
                            <svg class="h-3.5 w-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </span>
                    </div>
                </a>
            </div>
        `;

        list.insertAdjacentHTML('afterbegin', html);

        setTimeout(() => {
            const row = document.getElementById(`comment-row-${e.id}`);
            if (row) {
                row.classList.remove('opacity-0', '-translate-y-2');
            }
        }, 100);
    }
</script>
@endpush
