<x-home::layouts.master>
    <div class="bg-brand-secondary py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-5xl">Stock Information</h1>
            <p class="mt-4 text-lg leading-8 text-gray-300">Historical price data and market performance.</p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-16">
        @if($latestData)
        <!-- Current Summary -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 p-8 mb-12">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Closing Price</dt>
                    <dd class="mt-2 text-4xl font-bold tracking-tight text-brand-secondary">${{ number_format($latestData->closing_price, 2) }}</dd>
                    <p class="mt-2 text-sm {{ $latestData->price_change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $latestData->price_change >= 0 ? '+' : '' }}{{ number_format($latestData->price_change, 2) }} ({{ number_format($latestData->price_change_percent, 2) }}%)
                    </p>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Volume</dt>
                    <dd class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($latestData->volume) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Day Range</dt>
                    <dd class="mt-2 text-2xl font-semibold text-gray-900">${{ number_format($latestData->low_price, 2) }} - ${{ number_format($latestData->high_price, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Market Cap</dt>
                    <dd class="mt-2 text-2xl font-semibold text-gray-900">${{ number_format($latestData->market_cap / 1000000000, 2) }}B</dd>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-400 text-right">
                Data as of {{ $latestData->date->format('M d, Y') }}
            </div>
        </div>
        @endif

        <!-- Historical Data Table -->
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-base font-semibold leading-6 text-gray-900">Historical Data</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900">Date</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Open</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">High</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Low</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Close</th>
                            <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900">Volume</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($stockData as $data)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-gray-900">{{ $data->date->format('M d, Y') }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-500">${{ number_format($data->opening_price, 2) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-500">${{ number_format($data->high_price, 2) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-500">${{ number_format($data->low_price, 2) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-right text-sm font-medium text-gray-900">${{ number_format($data->closing_price, 2) }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-right text-sm text-gray-500">{{ number_format($data->volume) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-8">
            {{ $stockData->links() }}
        </div>
    </div>
</x-home::layouts.master>
