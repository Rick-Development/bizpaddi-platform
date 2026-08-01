<x-home::layouts.master>
    <div class="bg-brand-secondary py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-5xl">Financial Reports</h1>
            <p class="mt-4 text-lg leading-8 text-gray-300">Quarterly earnings, annual reports, and financial statements.</p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-16">
        @if($reports->count() > 0)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($reports as $report)
                    <div class="flex flex-col bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-all duration-300 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                {{ $report->fiscal_year }} {{ $report->report_period }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $report->report_date->format('M d, Y') }}</span>
                        </div>
                        <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-2">{{ $report->title }}</h3>
                        <p class="text-sm text-gray-600 mb-6 flex-grow line-clamp-3">{{ $report->summary }}</p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-100">
                            @if($report->getFirstMediaUrl('report_file'))
                                <a href="{{ $report->getFirstMediaUrl('report_file') }}" target="_blank" class="flex items-center justify-center w-full rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    Download PDF
                                </a>
                            @else
                                <span class="flex items-center justify-center w-full rounded-md bg-gray-50 px-3 py-2 text-sm font-semibold text-gray-400 cursor-not-allowed">
                                    Download Unavailable
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-10">
                {{ $reports->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-2 text-sm font-semibold text-gray-900">No reports found</h3>
                <p class="mt-1 text-sm text-gray-500">Check back later for financial updates.</p>
            </div>
        @endif
    </div>
</x-home::layouts.master>
