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
        background-color: rgba(255, 255, 255, 0.08);
    }
    .comment-card {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .comment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
        border-color: rgba(255, 255, 255, 0.2);
    }
</style>
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto w-full min-w-0">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white tracking-tight">Admin Task Comments Feed</h1>
        <p class="text-sm text-gray-400 mt-1">Real-time feed of comments posted across your workspace tasks and projects. Click any card to inspect & reply.</p>
    </div>

    {{-- Comments Timeline Feed --}}
    <div class="timeline-container space-y-6 w-full min-w-0" id="comments-timeline">
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

            <div class="relative flex gap-6 comment-row group w-full min-w-0 max-w-full" id="comment-row-{{ $comment->id }}">
                {{-- Commenter Avatar Indicator --}}
                <div class="relative z-10 flex-shrink-0">
                    @if($comment->user && method_exists($comment->user, 'getFirstMediaUrl') && $comment->user->getFirstMediaUrl('avatar'))
                        <img src="{{ $comment->user->getFirstMediaUrl('avatar') }}" class="h-10 w-10 rounded-full object-cover border-2 border-white/20 shadow-md" alt="">
                    @else
                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold text-xs border-2 border-white/20 shadow-md" style="background: linear-gradient(135deg, #0E5E4A, #10b981);">
                            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 2)) }}
                        </div>
                    @endif
                </div>

                {{-- Comment Content Card (Clickable to navigate to task) --}}
                <a href="{{ $taskUrl }}" 
                   class="flex-1 bg-[#10231C]/60 backdrop-blur-xl rounded-2xl border border-white/10 p-5 comment-card shadow-xl block no-underline group-hover:border-emerald-500/30 transition-all min-w-0 max-w-full overflow-hidden">
                    
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-white">{{ $comment->user->name ?? 'Unknown User' }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-white/[0.06] text-emerald-400 border border-emerald-500/20">
                                {{ str_contains($comment->user_type, 'Admin') ? 'Admin' : 'Team Member' }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-400 font-medium shrink-0">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>

                    {{-- Target Task reference --}}
                    <div class="mb-3 flex items-center flex-wrap gap-1.5">
                        <span class="text-xs text-gray-400">commented on</span>
                        <span class="text-xs font-bold text-emerald-400 group-hover:underline">
                            {{ $title }}
                        </span>
                        @if($parentName)
                            <span class="text-[10px] text-gray-400 font-medium bg-white/[0.03] px-2 py-0.5 rounded-md border border-white/10">
                                {{ $parentName }}
                            </span>
                        @endif
                    </div>

                    {{-- Text Content with break-words to guarantee no horizontal overflow --}}
                    <p class="text-xs md:text-sm text-gray-300 leading-relaxed font-normal mb-3 break-all break-words overflow-hidden whitespace-pre-wrap">{{ $comment->content }}</p>

                    {{-- Footer Action Link --}}
                    <div class="pt-3 border-t border-white/10 flex items-center justify-between text-xs text-gray-400 font-medium group-hover:text-emerald-400 transition-colors">
                        <span>Click to open task details & reply</span>
                        <span class="flex items-center gap-1 text-emerald-400 font-semibold text-xs">
                            View & Comment
                            <svg class="h-3.5 w-3.5 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </span>
                    </div>
                </a>
            </div>
        @empty
            <div class="bg-[#10231C]/60 backdrop-blur-xl rounded-2xl border border-white/10 p-12 text-center w-full" id="no-comments-msg">
                <div class="w-16 h-16 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="h-8 w-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-white">No Comments Yet</h3>
                <p class="mt-1 text-xs text-gray-400 max-w-sm mx-auto">Comments posted by your team on projects and tasks will appear here in real-time.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center">
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
            taskUrl = `/dashboard/tasks/${e.commentable_id}`;
        } else {
            taskUrl = `/dashboard/pm-projects/${e.commentable_id}`;
        }

        const list = document.getElementById('comments-timeline');
        
        let avatarHtml = '';
        if (e.user_avatar) {
            avatarHtml = `<img src="${e.user_avatar}" class="h-10 w-10 rounded-full object-cover border-2 border-white/20 shadow-md" alt="">`;
        } else {
            avatarHtml = `<div class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold text-xs border-2 border-white/20 shadow-md" style="background: linear-gradient(135deg, #0E5E4A, #10b981);">${e.user_name.substring(0, 2).toUpperCase()}</div>`;
        }

        const roleLabel = e.user_type.includes('Admin') ? 'Admin' : 'Team Member';

        const html = `
            <div class="relative flex gap-6 comment-row opacity-0 transform -translate-y-2 transition-all duration-500 group w-full min-w-0 max-w-full" id="comment-row-${e.id}">
                <div class="relative z-10 flex-shrink-0">${avatarHtml}</div>
                <a href="${taskUrl}" class="flex-1 bg-[#10231C]/60 backdrop-blur-xl rounded-2xl border border-white/10 p-5 comment-card shadow-xl block no-underline group-hover:border-emerald-500/30 transition-all min-w-0 max-w-full overflow-hidden">
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-white">${e.user_name}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-white/[0.06] text-emerald-400 border border-emerald-500/20">${roleLabel}</span>
                        </div>
                        <span class="text-xs text-gray-400 font-medium shrink-0">${e.formatted_time}</span>
                    </div>
                    <div class="mb-3 flex items-center flex-wrap gap-1.5">
                        <span class="text-xs text-gray-400">commented on</span>
                        <span class="text-xs font-bold text-emerald-400 group-hover:underline">${e.task_title}</span>
                    </div>
                    <p class="text-xs md:text-sm text-gray-300 leading-relaxed font-normal mb-3 break-all break-words overflow-hidden whitespace-pre-wrap">${e.content}</p>
                    <div class="pt-3 border-t border-white/10 flex items-center justify-between text-xs text-gray-400 font-medium group-hover:text-emerald-400 transition-colors">
                        <span>Click to open task details & reply</span>
                        <span class="flex items-center gap-1 text-emerald-400 font-semibold text-xs">
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
