@extends('admin::layouts.app')

@section('title', 'Balance Sheet')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">Balance Sheet</h1>
    <p class="mt-1 text-sm text-gray-400">View your financial position</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-white/10 p-6 group hover:shadow-md transition-all duration-300">
        <div class="text-sm font-medium text-gray-400 mb-2">Total Assets</div>
        <div class="text-3xl font-bold text-white group-hover:text-green-600 transition-colors">${{ number_format($totalAssets ?? 0, 2) }}</div>
    </div>
    <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-white/10 p-6 group hover:shadow-md transition-all duration-300">
        <div class="text-sm font-medium text-gray-400 mb-2">Total Liabilities</div>
        <div class="text-3xl font-bold text-white group-hover:text-red-600 transition-colors">${{ number_format($totalLiabilities ?? 0, 2) }}</div>
    </div>
    <div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-white/10 p-6 group hover:shadow-md transition-all duration-300">
        <div class="text-sm font-medium text-gray-400 mb-2">Total Equity</div>
        <div class="text-3xl font-bold text-white group-hover:text-blue-600 transition-colors">${{ number_format($totalEquity ?? 0, 2) }}</div>
    </div>
</div>

<div class="bg-[#10231C]/60 rounded-2xl shadow-sm border border-white/10 overflow-hidden">
    <div class="p-6 border-b border-white/10 bg-white/[0.03]/50">
        <h2 class="text-lg font-bold text-white">Account Balances</h2>
    </div>
    @if($categories->isEmpty())
    <div class="p-12 text-center text-gray-400">
        No accounts found. Create accounts to see your balance sheet.
    </div>
    @else
    <div class="divide-y divide-white/5">
        @foreach($categories as $category)
        <div class="p-6">
            <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">{{ $category->name }}</h3>
            @if($category->accounts->isEmpty())
            <p class="text-sm text-gray-400 italic">No accounts in this category</p>
            @else
            <div class="space-y-3">
                @foreach($category->accounts as $account)
                <div class="flex justify-between items-center group">
                    <div class="flex items-center">
                        <span class="text-sm font-mono text-gray-400 w-16">{{ $account->code }}</span>
                        <span class="text-sm font-medium text-gray-300 group-hover:text-white transition-colors">{{ $account->name }}</span>
                    </div>
                    <span class="text-sm font-semibold text-white group-hover:text-brand-primary transition-colors">${{ number_format($account->calculateBalance(), 2) }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
