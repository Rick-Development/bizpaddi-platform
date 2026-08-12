@extends('clientportal::layouts.app')

@section('title', $project->name)

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('client.projects.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $project->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Project Details</p>
        </div>
        <div class="ml-auto flex items-center gap-3">
             <a href="{{ route('client.projects.chat', $project) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                Chat with Team
            </a>
             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    {{ $project->status === 'completed' ? 'bg-green-100 text-green-800' : 
                       ($project->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
            </span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Details & Tasks -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Description -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
            <div class="prose prose-sm max-w-none text-gray-600">
                {!! $project->description !!}
            </div>
        </div>

        <!-- Tasks -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Milestones & Tasks</h3>
            @if($project->tasks->isEmpty())
                <p class="text-sm text-gray-500">No tasks assigned yet.</p>
            @else
                <div class="flow-root">
                    <ul role="list" class="-my-5 divide-y divide-gray-100">
                        @foreach($project->tasks as $task)
                        <li class="py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0 text-xs font-bold px-2 py-1 rounded bg-gray-100">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $task->title }}</p>
                                    <p class="text-xs text-gray-500 truncate">Status: <span class="capitalize">{{ $task->status }}</span></p>
                                </div>
                                @if($task->status == 'done')
                                <span class="text-green-500">✅</span>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Collaboration -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                <nav class="-mb-px flex space-x-6">
                    <button type="button" onclick="switchTab('comments')" id="btn-comments" class="border-black text-black whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Project Discussion
                    </button>
                    <button type="button" onclick="switchTab('approvals')" id="btn-approvals" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-black whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Approvals ({{ $project->tasks->where('requires_client_approval', true)->where('approval_status', 'pending')->count() }})
                    </button>
                    <button type="button" onclick="switchTab('files')" id="btn-files" class="border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Shared Files
                    </button>
                </nav>
            </div>
            
            <div class="p-6">
                <div id="section-comments">
                    <form action="{{ route('pm.comments.store') }}" method="POST" class="mb-8">
                        @csrf
                        <input type="hidden" name="commentable_id" value="{{ $project->id }}">
                        <input type="hidden" name="commentable_type" value="project">
                        <textarea name="content" rows="3" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-black focus:ring-black sm:text-sm" placeholder="Ask a question or provide feedback..."></textarea>
                        <div class="mt-3 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-black text-white text-sm font-medium rounded-xl hover:bg-gray-800 transition-colors">
                                Post to Discussion
                            </button>
                        </div>
                    </form>

                    <div class="space-y-6">
                        @forelse($project->comments->where('is_internal', false) as $comment)
                        <div class="flex gap-4">
                            <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-bold text-gray-400 capitalize">
                                {{ substr($comment->user->name ?? 'C', 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-bold text-gray-900">{{ $comment->user->name ?? 'Client' }}</span>
                                    <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $comment->content }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-sm text-gray-500 py-4">Start a discussion about your project.</p>
                        @endforelse
                    </div>
                </div>

                <div id="section-approvals" class="hidden">
                    <div class="space-y-6">
                        @forelse($project->tasks->where('requires_client_approval', true) as $task)
                        <div class="p-6 border border-gray-100 rounded-2xl bg-gray-50">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900">{{ $task->title }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit(strip_tags($task->description), 100) }}</p>
                                </div>
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase
                                    @if($task->approval_status === 'approved') bg-green-100 text-green-800
                                    @elseif($task->approval_status === 'changes_requested') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $task->approval_status ?: 'Pending' }}
                                </span>
                            </div>

                            @if($task->approval_status !== 'approved')
                            <div class="flex gap-3">
                                <form action="{{ route('client.tasks.approve', $task) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:bg-green-700 transition-colors">
                                        Approve
                                    </button>
                                </form>
                                <button type="button" onclick="openChangesModal('{{ $task->id }}', '{{ $task->title }}')" class="px-3 py-1.5 border border-red-200 text-red-600 text-xs font-bold rounded-lg hover:bg-red-50 transition-colors">
                                    Request Changes
                                </button>
                            </div>
                            @else
                            <div class="flex items-center text-xs text-green-600 font-bold">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Approved on {{ $task->approved_at->format('M d, Y') }}
                            </div>
                            @endif
                        </div>
                        @empty
                        <div class="py-8 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                             <p class="text-sm text-gray-500 font-medium">No tasks currently require your approval.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div id="section-files" class="hidden">
                    <div class="space-y-8">
                        <!-- Project Vault -->
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 mb-4">Project Vault (Master Documents)</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @forelse($project->getMedia('vault') as $media)
                                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl bg-white shadow-sm">
                                    <div class="flex items-center overflow-hidden">
                                        <div class="h-10 w-10 bg-brand-primary/10 rounded-lg flex items-center justify-center mr-3 shrink-0">
                                            <span class="text-[10px] font-bold text-brand-primary uppercase">{{ $media->extension }}</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $media->file_name }}</p>
                                            <p class="text-[10px] text-gray-500">{{ $media->human_readable_size }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('pm.attachments.download', $media) }}" download class="ml-4 text-gray-400 hover:text-brand-primary transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor font-medium">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </div>
                                @empty
                                <div class="col-span-full py-8 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                    <p class="text-sm text-gray-500 font-medium">No master documents shared yet.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Task Attachments -->
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 mb-4">Task Attachments</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @php
                                    $allTaskMedia = collect();
                                    foreach($project->tasks as $task) {
                                        $allTaskMedia = $allTaskMedia->merge($task->getMedia('attachments')->filter(fn($m) => !$m->getCustomProperty('is_internal')));
                                    }
                                @endphp
                                @forelse($allTaskMedia as $media)
                                <div class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl bg-white shadow-sm">
                                    <div class="flex items-center overflow-hidden">
                                        <div class="h-10 w-10 bg-gray-50 border border-gray-100 rounded-lg flex items-center justify-center mr-3 shrink-0">
                                            <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $media->extension }}</span>
                                        </div>
                                        <div class="truncate">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $media->file_name }}</p>
                                            <p class="text-[10px] text-gray-500">{{ $media->human_readable_size }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('pm.attachments.download', $media) }}" download class="ml-4 text-gray-400 hover:text-black transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor font-medium">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </div>
                                @empty
                                <div class="col-span-full py-8 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                    <p class="text-sm text-gray-500 font-medium">No task files shared yet.</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Meta -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Project Information</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $project->due_date ? $project->due_date->format('M d, Y') : 'Not set' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Completion Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $project->completed_at ? $project->completed_at->format('M d, Y') : 'In Progress' }}</dd>
                </div>
                
                 @if($project->budget)
                <div class="pt-4 border-t border-gray-100">
                    <dt class="text-sm font-medium text-gray-500">Budget</dt>
                     <dd class="mt-1 text-sm text-gray-900">
                        {{ $project->currency ? $project->currency->symbol : '$' }}{{ number_format($project->budget, 2) }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<div id="changes-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeChangesModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="changes-form" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4" id="modal-title">Request Changes</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Feedback / Required Changes</label>
                        <textarea name="client_feedback" rows="4" required class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-black focus:ring-black sm:text-sm" placeholder="Please describe what needs to be changed..."></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:w-auto sm:text-sm">
                        Submit Feedback
                    </button>
                    <button type="button" onclick="closeChangesModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function switchTab(tab) {
    document.getElementById('section-comments').classList.toggle('hidden', tab !== 'comments');
    document.getElementById('section-approvals').classList.toggle('hidden', tab !== 'approvals');
    document.getElementById('section-files').classList.toggle('hidden', tab !== 'files');
    
    document.getElementById('btn-comments').classList.toggle('border-black', tab === 'comments');
    document.getElementById('btn-comments').classList.toggle('text-black', tab === 'comments');
    document.getElementById('btn-comments').classList.toggle('border-transparent', tab !== 'comments');
    document.getElementById('btn-comments').classList.toggle('text-gray-500', tab !== 'comments');

    document.getElementById('btn-approvals').classList.toggle('border-black', tab === 'approvals');
    document.getElementById('btn-approvals').classList.toggle('text-black', tab === 'approvals');
    document.getElementById('btn-approvals').classList.toggle('border-transparent', tab !== 'approvals');
    document.getElementById('btn-approvals').classList.toggle('text-gray-500', tab !== 'approvals');

    document.getElementById('btn-files').classList.toggle('border-black', tab === 'files');
    document.getElementById('btn-files').classList.toggle('text-black', tab === 'files');
    document.getElementById('btn-files').classList.toggle('border-transparent', tab !== 'files');
    document.getElementById('btn-files').classList.toggle('text-gray-500', tab !== 'files');
}

function openChangesModal(taskId, taskTitle) {
    const form = document.getElementById('changes-form');
    form.action = `/client/tasks/${taskId}/reject`;
    document.getElementById('modal-title').innerText = `Request Changes: ${taskTitle}`;
    document.getElementById('changes-modal').classList.remove('hidden');
}

function closeChangesModal() {
    document.getElementById('changes-modal').classList.add('hidden');
}
</script>
@endpush
