@extends('teamportal::layouts.app')

@section('title', 'Task Comments Feed')

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
        background-color: rgba(255, 255, 255, 0.1);
    }
    .comment-card {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .comment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.4);
        border-color: rgba(16, 185, 129, 0.4);
    }
</style>
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto space-y-8 animate-fade-in">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Task & Project Comments Feed</h1>
        <p class="text-sm text-gray-400 mt-1">Real-time feed of comments dropped on your tasks and projects. Click any card to open the discussion room.</p>
    </div>

    {{-- Comments Timeline Feed --}}
    <div class="timeline-container space-y-6" id="comments-timeline">
        @forelse($comments as $comment)
            @php
                $commentable = $comment->commentable;
                $targetUrl = '#';
                $title = 'Unknown Item';
                $parentName = '';
                $isTask = false;

                if ($comment->commentable_type === \Modules\ProjectManagement\Models\Task::class) {
                    $isTask = true;
                    $title = $commentable ? $commentable->title : 'Deleted Task';
                    $parentName = ($commentable && $commentable->project) ? $commentable->project->name : '';
                    if ($commentable) {
                        $targetUrl = route('team.tasks.show', $commentable->id);
                    }
                } elseif ($comment->commentable_type === \Modules\ProjectManagement\Models\Project::class) {
                    $isTask = false;
                    $title = $commentable ? $commentable->name : 'Deleted Project';
                    $parentName = '';
                    if ($commentable) {
                        if (auth()->user()->isProjectManager()) {
                            $targetUrl = route('team.pm.projects.show', $commentable->id);
                        } else {
                            $targetUrl = route('team.projects.show', $commentable->id);
                        }
                    }
                }
            @endphp

            <div class="relative flex gap-6 comment-row group" id="comment-row-{{ $comment->id }}">
                {{-- Commenter Avatar Indicator --}}
                <div class="relative z-10 flex-shrink-0">
                    @if($comment->user && method_exists($comment->user, 'getFirstMediaUrl') && $comment->user->getFirstMediaUrl('avatar'))
                        <img src="{{ $comment->user->getFirstMediaUrl('avatar') }}" class="h-10 w-10 rounded-full object-cover border-2 border-emerald-500/30 shadow-md" alt="">
                    @else
                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-emerald-400 font-bold text-xs border-2 border-emerald-500/30 bg-[#10231C] shadow-md">
                            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 2)) }}
                        </div>
                    @endif
                </div>

                {{-- Comment Content Card (Clickable) --}}
                <a href="{{ $targetUrl }}" 
                   class="flex-1 bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 p-5 comment-card shadow-xl block no-underline transition-all">
                    
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-white">{{ $comment->user->name ?? 'Unknown User' }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-white/5 border border-white/10 text-gray-300">
                                {{ str_contains($comment->user_type, 'Admin') ? 'Admin' : 'Team Member' }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-400 font-medium">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>

                    {{-- Target Reference Badge --}}
                    <div class="mb-3 flex items-center flex-wrap gap-1.5">
                        <span class="text-xs text-gray-400">commented on</span>
                        <span class="text-xs font-bold text-emerald-400 group-hover:underline">
                            {{ $title }}
                        </span>
                        @if($isTask)
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-blue-500/20 text-blue-300 border border-blue-500/30">
                                Task
                            </span>
                        @else
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-purple-500/20 text-purple-300 border border-purple-500/30">
                                Project
                            </span>
                        @endif
                        @if($parentName)
                            <span class="text-[10px] text-gray-400 font-medium bg-white/5 px-2 py-0.5 rounded-md border border-white/10">
                                {{ $parentName }}
                            </span>
                        @endif
                    </div>

                    {{-- Text Content --}}
                    <p class="text-xs md:text-sm text-gray-200 leading-relaxed font-normal mb-3">{{ $comment->content }}</p>

                    {{-- Footer Action Link --}}
                    <div class="pt-3 border-t border-white/5 flex items-center justify-between text-xs text-gray-400 font-medium group-hover:text-emerald-400 transition-colors">
                        <span>{{ $isTask ? 'Click to open task details & reply' : 'Click to open project room & reply' }}</span>
                        <span class="flex items-center gap-1 text-emerald-400 font-bold text-xs">
                            {{ $isTask ? 'Open Task →' : 'Open Project →' }}
                        </span>
                    </div>
                </a>
            </div>
        @empty
            <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 p-12 text-center" id="no-comments-msg">
                <svg class="mx-auto h-12 w-12 text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h3 class="text-sm font-bold text-white">No Comments Yet</h3>
                <p class="mt-1 text-xs text-gray-400">Comments posted on your tasks or projects will stream live here.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($comments->hasPages())
    <div class="mt-6">
        {{ $comments->links() }}
    </div>
    @endif
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
        const isPm = "{{ auth()->user()->isProjectManager() ? 'true' : 'false' }}" === 'true';
        const currentUserId = {{ auth()->id() }};
        
        let shouldShow = false;
        if (isPm) {
            if (e.manager_id === currentUserId || e.user_id === currentUserId) shouldShow = true;
        } else {
            if (e.assigned_to_id === currentUserId || e.user_id === currentUserId || (e.project_id && checkProjectMembership(e.project_id))) {
                shouldShow = true;
            }
        }

        if (!shouldShow) return;

        const noComments = document.getElementById('no-comments-msg');
        if (noComments) noComments.classList.add('hidden');

        let targetUrl = '#';
        const isTask = e.commentable_type.includes('Task');
        if (isPm) {
            targetUrl = `/dashboard/team/pm/projects/${e.project_id}`;
        } else {
            targetUrl = isTask ? `/dashboard/team/tasks/${e.commentable_id}` : `/dashboard/team/projects/${e.commentable_id}`;
        }

        const list = document.getElementById('comments-timeline');
        
        let avatarHtml = '';
        if (e.user_avatar) {
            avatarHtml = `<img src="${e.user_avatar}" class="h-10 w-10 rounded-full object-cover border-2 border-emerald-500/30 shadow-md" alt="">`;
        } else {
            avatarHtml = `<div class="h-10 w-10 rounded-full flex items-center justify-center text-emerald-400 font-bold text-xs border-2 border-emerald-500/30 bg-[#10231C] shadow-md">${e.user_name.substring(0, 2).toUpperCase()}</div>`;
        }

        const roleLabel = e.user_type.includes('Admin') ? 'Admin' : 'Team Member';
        const typeBadge = isTask 
            ? '<span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-blue-500/20 text-blue-300 border border-blue-500/30">Task</span>'
            : '<span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-purple-500/20 text-purple-300 border border-purple-500/30">Project</span>';

        const html = `
            <div class="relative flex gap-6 comment-row opacity-0 transform -translate-y-2 transition-all duration-500 group" id="comment-row-${e.id}">
                <div class="relative z-10 flex-shrink-0">${avatarHtml}</div>
                <a href="${targetUrl}" class="flex-1 bg-[#10231C]/60 backdrop-blur-sm rounded-2xl border border-white/10 p-5 comment-card shadow-xl block no-underline transition-all">
                    <div class="flex items-center justify-between gap-4 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-white">${e.user_name}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-white/5 border border-white/10 text-gray-300">${roleLabel}</span>
                        </div>
                        <span class="text-xs text-gray-400 font-medium">${e.formatted_time}</span>
                    </div>
                    <div class="mb-3 flex items-center flex-wrap gap-1.5">
                        <span class="text-xs text-gray-400">commented on</span>
                        <span class="text-xs font-bold text-emerald-400 group-hover:underline">${e.task_title}</span>
                        ${typeBadge}
                    </div>
                    <p class="text-xs md:text-sm text-gray-200 leading-relaxed font-normal mb-3">${e.content}</p>
                    <div class="pt-3 border-t border-white/5 flex items-center justify-between text-xs text-gray-400 font-medium group-hover:text-emerald-400 transition-colors">
                        <span>${isTask ? 'Click to open task details & reply' : 'Click to open project room & reply'}</span>
                        <span class="flex items-center gap-1 text-emerald-400 font-bold text-xs">
                            ${isTask ? 'Open Task →' : 'Open Project →'}
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

    function checkProjectMembership(projectId) {
        return true;
    }
</script>
@endpush
