@extends('clientportal::layouts.app')

@section('title', 'Project Chat - ' . $project->name)

@section('content')
<div class="h-[calc(100vh-8rem)] flex flex-col">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between shrink-0">
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $project->name }}</h1>
            <p class="text-sm text-gray-500">Secure Team Communication</p>
        </div>
        <div class="flex items-center gap-3">
             <a href="{{ route('client.projects.show', $project) }}" class="text-sm font-semibold text-gray-900 hover:text-brand-primary">
                &larr; Back to Project
            </a>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-gray-50 bg-repeat bg-center" style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%239C92AC\' fill-opacity=\'0.05\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');">
        @forelse($messages->reverse() as $message)
            @php
                $isMe = $message->sender_id === auth('client')->id() && $message->sender_type === get_class(auth('client')->user());
                $isTeam = str_contains($message->sender_type, 'User'); // Needs robust check if namespace changes
            @endphp

            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="flex max-w-xl {{ $isMe ? 'flex-row-reverse' : 'flex-row' }} gap-3">
                    <!-- Avatar -->
                    <div class="shrink-0 h-10 w-10 min-w-[2.5rem] rounded-full flex items-center justify-center font-bold text-sm shadow-sm
                        {{ $isTeam ? 'bg-blue-100 text-blue-600' : ($isMe ? 'bg-brand-primary text-white' : 'bg-gray-200 text-gray-600') }}">
                        @if($isTeam)
                            T
                        @elseif($isMe)
                            Me
                        @else
                            ?
                        @endif
                    </div>

                    <!-- Message Bubble -->
                    <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                        <div class="flex items-baseline gap-2 mb-1">
                            <span class="text-xs font-bold {{ $isMe ? 'text-gray-900' : ($isTeam ? 'text-blue-700' : 'text-gray-700') }}">
                                {{ $isMe ? 'You' : $message->sender->name }}
                            </span>
                            <span class="text-[10px] text-gray-400">
                                {{ $message->created_at->format('M d, H:i') }}
                            </span>
                        </div>
                        <div class="px-4 py-3 rounded-2xl shadow-sm text-sm leading-relaxed
                            {{ $isMe 
                                ? 'bg-brand-secondary text-white rounded-tr-none' 
                                : ($isTeam ? 'bg-white border border-blue-100 rounded-tl-none' : 'bg-white border border-gray-100 rounded-tl-none') 
                            }}">
                            {!! nl2br(e($message->content)) !!}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center h-full text-gray-400 space-y-4">
                 <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p>Start a conversation with the project team.</p>
                <p class="text-xs max-w-xs text-center">Your messages go directly to the assigned team members.</p>
            </div>
        @endforelse
    </div>

    <!-- Input Area -->
    <div class="bg-white px-6 py-4 border-t border-gray-200 shrink-0">
        <form action="{{ route('client.projects.chat.store', $project) }}" method="POST" class="relative">
            @csrf
            <div class="relative rounded-xl shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-brand-primary">
                <textarea rows="2" name="content" id="content"
                    class="block w-full rounded-xl border-0 py-3 text-gray-900 shadow-sm placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6 resize-none pr-12"
                    placeholder="Message the team..." required></textarea>
                
                <div class="absolute bottom-2 right-2">
                    <button type="submit" class="inline-flex items-center rounded-lg bg-brand-primary p-2 text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M3.105 2.289a.75.75 0 00-.826.95l1.414 4.925A2.001 2.001 0 005.692 10H14a1 1 0 010 2H5.692a2.001 2.001 0 00-1.999 1.836l-1.414 4.925a.75.75 0 00.826.95 28.89 28.89 0 0015.293-7.154.75.75 0 000-1.115A28.897 28.897 0 003.105 2.289z" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto scroll to bottom
    const chatContainer = document.querySelector('.overflow-y-auto');
    chatContainer.scrollTop = chatContainer.scrollHeight;
</script>
@endsection
