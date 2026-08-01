<x-home::layouts.master>
    <!-- Hero Section -->
    <div class="relative bg-brand-secondary py-24 sm:py-32">
        <div class="absolute inset-0 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Office buildings" class="h-full w-full object-cover opacity-10">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-secondary via-brand-secondary/90 to-brand-primary/80 mix-blend-multiply"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">Investor Relations</h1>
            <p class="mt-6 text-lg leading-8 text-gray-300 max-w-2xl mx-auto">
                Transparent financial information, corporate governance, and shareholder resources for our investors and stakeholders.
            </p>
        </div>
    </div>

    <!-- Key Metrics / Stock Ticker -->
    <div class="bg-white border-b border-gray-200">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-y-8 sm:grid-cols-2 lg:grid-cols-4 py-8 divide-y sm:divide-y-0 sm:divide-x divide-gray-200">
                @if($latestStockData)
                <div class="px-4 text-center">
                    <dt class="text-sm font-medium leading-6 text-gray-500">Current Stock Price</dt>
                    <dd class="mt-2 text-3xl font-bold tracking-tight text-brand-secondary">
                        ${{ number_format($latestStockData->closing_price, 2) }}
                    </dd>
                    <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $latestStockData->price_change >= 0 ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-red-50 text-red-700 ring-red-600/20' }} mt-2">
                        {{ $latestStockData->price_change >= 0 ? '+' : '' }}{{ number_format($latestStockData->price_change, 2) }} ({{ number_format($latestStockData->price_change_percent, 2) }}%)
                    </span>
                </div>
                <div class="px-4 text-center">
                    <dt class="text-sm font-medium leading-6 text-gray-500">Market Cap</dt>
                    <dd class="mt-2 text-3xl font-bold tracking-tight text-gray-900">${{ number_format($latestStockData->market_cap / 1000000000, 2) }}B</dd>
                    <p class="mt-1 text-xs text-gray-500">As of {{ $latestStockData->date->format('M d, Y') }}</p>
                </div>
                @else
                <div class="px-4 text-center col-span-2">
                    <p class="text-gray-500 italic">Stock data currently unavailable</p>
                </div>
                @endif
                
                <div class="px-4 text-center">
                    <dt class="text-sm font-medium leading-6 text-gray-500">Next Earnings Call</dt>
                    @if($upcomingMeeting)
                        <dd class="mt-2 text-xl font-bold tracking-tight text-gray-900">{{ $upcomingMeeting->meeting_date->format('M d, Y') }}</dd>
                        <p class="mt-1 text-xs text-gray-500">{{ $upcomingMeeting->meeting_type_label }}</p>
                    @else
                        <dd class="mt-2 text-xl font-bold tracking-tight text-gray-900">TBA</dd>
                    @endif
                </div>
                
                <div class="px-4 text-center">
                    <dt class="text-sm font-medium leading-6 text-gray-500">Fiscal Year</dt>
                    <dd class="mt-2 text-3xl font-bold tracking-tight text-gray-900">{{ date('Y') }}</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links Grid -->
    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-16 sm:py-24">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Financial Reports -->
            <div class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-brand-primary rounded-2xl shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-x-4">
                    <span class="inline-flex rounded-lg bg-blue-50 p-3 text-blue-700 ring-1 ring-inset ring-blue-700/10 group-hover:bg-blue-100 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </span>
                    <h3 class="text-lg font-semibold leading-6 text-gray-900">
                        <a href="{{ route('investor.financial-reports') }}" class="focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            Financial Reports
                        </a>
                    </h3>
                </div>
                <p class="mt-4 text-sm leading-6 text-gray-600">Access our quarterly and annual reports, earnings presentations, and financial statements.</p>
                
                @if($latestReports->count() > 0)
                <ul class="mt-6 space-y-3 border-t border-gray-100 pt-4">
                    @foreach($latestReports as $report)
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 truncate max-w-[200px]">{{ $report->title }}</span>
                        <span class="text-gray-400 text-xs">{{ $report->report_date->format('M Y') }}</span>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>

            <!-- Press Releases -->
            <div class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-brand-primary rounded-2xl shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-x-4">
                    <span class="inline-flex rounded-lg bg-purple-50 p-3 text-purple-700 ring-1 ring-inset ring-purple-700/10 group-hover:bg-purple-100 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                    </span>
                    <h3 class="text-lg font-semibold leading-6 text-gray-900">
                        <a href="{{ route('investor.press-releases.index') }}" class="focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            Press Releases
                        </a>
                    </h3>
                </div>
                <p class="mt-4 text-sm leading-6 text-gray-600">Latest news, corporate announcements, and updates for our shareholders.</p>
                
                @if($latestPressReleases->count() > 0)
                <ul class="mt-6 space-y-3 border-t border-gray-100 pt-4">
                    @foreach($latestPressReleases as $release)
                    <li class="flex items-center justify-between text-sm">
                        <span class="text-gray-600 truncate max-w-[200px]">{{ $release->title }}</span>
                        <span class="text-gray-400 text-xs">{{ $release->published_at->format('M d') }}</span>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>

            <!-- Shareholder Meetings -->
            <div class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-brand-primary rounded-2xl shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-all duration-300">
                <div class="flex items-center gap-x-4">
                    <span class="inline-flex rounded-lg bg-orange-50 p-3 text-orange-700 ring-1 ring-inset ring-orange-700/10 group-hover:bg-orange-100 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </span>
                    <h3 class="text-lg font-semibold leading-6 text-gray-900">
                        <a href="{{ route('investor.shareholder-meetings') }}" class="focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            Shareholder Meetings
                        </a>
                    </h3>
                </div>
                <p class="mt-4 text-sm leading-6 text-gray-600">Information on Annual General Meetings, proxy voting, and shareholder events.</p>
                
                @if($upcomingMeeting)
                <div class="mt-6 border-t border-gray-100 pt-4">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Next Meeting</p>
                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $upcomingMeeting->title }}</p>
                    <p class="text-xs text-gray-500">{{ $upcomingMeeting->meeting_date->format('F d, Y \a\t h:i A') }}</p>
                </div>
                @else
                <div class="mt-6 border-t border-gray-100 pt-4">
                    <p class="text-sm text-gray-500 italic">No upcoming meetings scheduled.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Stock Chart Section (Placeholder for now, could be dynamic) -->
    <div class="bg-gray-50 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Stock Performance</h2>
                <p class="mt-2 text-lg leading-8 text-gray-600">Historical performance of Rick Development stock.</p>
            </div>
            <div class="mt-10 bg-white p-6 rounded-2xl shadow-sm ring-1 ring-gray-900/5">
                <!-- Simple visual representation since we don't have a JS charting library set up yet -->
                <div class="relative h-96 w-full flex items-end justify-between gap-2 px-4 pb-4 border-b border-gray-200">
                    <!-- Placeholder bars -->
                    @for($i = 0; $i < 20; $i++)
                        @php $height = rand(40, 90); @endphp
                        <div class="w-full bg-brand-primary/10 hover:bg-brand-primary/20 transition-colors rounded-t-sm relative group" style="height: {{ $height }}%">
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-gray-900 text-white text-xs rounded px-2 py-1 whitespace-nowrap z-10">
                                ${{ number_format($height * 1.5, 2) }}
                            </div>
                        </div>
                    @endfor
                </div>
                <div class="mt-4 flex justify-between text-xs text-gray-500">
                    <span>30 Days Ago</span>
                    <span>Today</span>
                </div>
                <div class="mt-8 text-center">
                    <a href="{{ route('investor.stock-data') }}" class="text-sm font-semibold leading-6 text-brand-primary hover:text-brand-secondary">
                        View Full Stock Data <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-home::layouts.master>
