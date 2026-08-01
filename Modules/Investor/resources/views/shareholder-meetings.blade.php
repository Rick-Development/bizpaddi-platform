<x-home::layouts.master>
    <div class="bg-brand-secondary py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-5xl">Shareholder Meetings</h1>
            <p class="mt-4 text-lg leading-8 text-gray-300">Information on Annual General Meetings and other shareholder events.</p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-16">
        <!-- Upcoming Meetings -->
        @if($upcomingMeetings->count() > 0)
        <div class="mb-16">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 mb-8">Upcoming Meetings</h2>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                @foreach($upcomingMeetings as $meeting)
                <div class="bg-white rounded-2xl shadow-lg ring-1 ring-gray-900/5 p-8 border-l-4 border-brand-primary">
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center rounded-md bg-brand-primary/10 px-2 py-1 text-xs font-medium text-brand-primary ring-1 ring-inset ring-brand-primary/20">
                            {{ $meeting->meeting_type_label }}
                        </span>
                        <span class="text-sm font-semibold text-gray-900">{{ $meeting->meeting_date->format('F d, Y \a\t h:i A') }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $meeting->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ $meeting->location }}</p>
                    <p class="text-gray-600 mb-6">{{ $meeting->description }}</p>
                    
                    <div class="flex flex-wrap gap-3">
                        @if($meeting->getFirstMediaUrl('agenda_file'))
                        <a href="{{ $meeting->getFirstMediaUrl('agenda_file') }}" target="_blank" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <svg class="mr-1.5 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            Agenda
                        </a>
                        @endif
                        
                        @if($meeting->getFirstMediaUrl('presentation_file'))
                        <a href="{{ $meeting->getFirstMediaUrl('presentation_file') }}" target="_blank" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <svg class="mr-1.5 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                            </svg>
                            Presentation
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Past Meetings -->
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 mb-8">Past Meetings</h2>
            <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
                <ul role="list" class="divide-y divide-gray-100">
                    @foreach($pastMeetings as $meeting)
                    <li class="relative flex justify-between gap-x-6 px-4 py-5 hover:bg-gray-50 sm:px-6">
                        <div class="flex min-w-0 gap-x-4">
                            <div class="min-w-0 flex-auto">
                                <p class="text-sm font-semibold leading-6 text-gray-900">
                                    {{ $meeting->title }}
                                </p>
                                <div class="mt-1 flex items-center gap-x-2 text-xs leading-5 text-gray-500">
                                    <p class="truncate">{{ $meeting->meeting_date->format('M d, Y') }}</p>
                                    <svg viewBox="0 0 2 2" class="h-0.5 w-0.5 fill-current"><circle cx="1" cy="1" r="1" /></svg>
                                    <p>{{ $meeting->meeting_type_label }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-x-4">
                            <div class="hidden sm:flex sm:flex-col sm:items-end">
                                <div class="flex gap-2">
                                    @if($meeting->getFirstMediaUrl('minutes_file'))
                                    <a href="{{ $meeting->getFirstMediaUrl('minutes_file') }}" target="_blank" class="text-xs font-medium text-brand-primary hover:text-brand-secondary hover:underline">Minutes</a>
                                    @endif
                                    
                                    @if($meeting->getFirstMediaUrl('presentation_file'))
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ $meeting->getFirstMediaUrl('presentation_file') }}" target="_blank" class="text-xs font-medium text-brand-primary hover:text-brand-secondary hover:underline">Presentation</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-6">
                {{ $pastMeetings->links() }}
            </div>
        </div>
    </div>
</x-home::layouts.master>
