<x-home::layouts.master>
<div class="relative bg-white py-16 sm:py-24">
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">
                {{ __('Careers') }}
            </h2>
            <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-500">
                {{ __('Explore our current job openings and find your next opportunity.') }}
            </p>
        </div>

        @if(isset($jobs) && $jobs->count() > 0)
            <div class="mt-12 max-w-lg mx-auto grid gap-5 lg:grid-cols-1 lg:max-w-none">
                <div class="space-y-4">
                    @foreach($jobs as $job)
                        <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">{{ $job->title }}</h4>
                                    <div class="mt-2 flex items-center gap-4 text-sm text-gray-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            {{ $job->department }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $job->location }}
                                        </span>
                                        <span class="flex items-center gap-1 bg-gray-100 px-2 py-0.5 rounded text-xs font-semibold text-gray-600">
                                            {{ ucfirst($job->type) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-4 sm:mt-0">
                                    <a href="{{ route('careers.show', $job->id) }}" class="inline-flex items-center text-sm font-semibold text-brand-primary hover:text-brand-primary/80">
                                        Apply Now &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center mt-16">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('No open positions') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('Check back soon for new opportunities.') }}</p>
            </div>
        @endif
    </div>
</div>
</x-home::layouts.master>
