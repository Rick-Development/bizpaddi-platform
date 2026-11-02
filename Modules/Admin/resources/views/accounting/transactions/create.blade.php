@extends('admin::layouts.app')

@section('title', 'Record Transaction')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.accounting.transactions.index') }}" class="text-gray-400 hover:text-gray-400">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Record Transaction</h1>
            <p class="mt-1 text-sm text-gray-400">Add a new financial transaction</p>
        </div>
    </div>
</div>

<div class="max-w-2xl">
    <form action="{{ route('admin.accounting.transactions.store') }}" method="POST" class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl">
        @csrf
        
        <div class="p-6 space-y-6">
            <!-- Account -->
            <div>
                <label for="account_id" class="block text-sm font-medium text-gray-300 mb-2">Account *</label>
                <select name="account_id" id="account_id" required
                    class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors @error('account_id') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">
                    <option value="">Select an account</option>
                    @foreach($accounts as $account)
                    <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                        {{ $account->name }} ({{ $account->code }})
                    </option>
                    @endforeach
                </select>
                @error('account_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @if($accounts->isEmpty())
                <p class="mt-1 text-sm text-orange-600">
                    No accounts found. Please <a href="{{ route('admin.accounting.accounts.create') }}" class="underline hover:text-orange-800">create an account</a> first.
                </p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Date -->
                <div>
                    <label for="transaction_date" class="block text-sm font-medium text-gray-300 mb-2">Date *</label>
                    <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors @error('transaction_date') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">
                    @error('transaction_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Reference -->
                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-300 mb-2">Reference Ref</label>
                    <input type="text" name="reference" id="reference" value="{{ old('reference') }}" placeholder="e.g. INV-001"
                        class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors @error('reference') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">
                    @error('reference')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description *</label>
                <textarea name="description" id="description" rows="2" required placeholder="Transaction details"
                    class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors @error('description') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-300 mb-2">Type *</label>
                    <select name="type" id="type" required
                        class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors @error('type') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">
                        <option value="debit" {{ old('type') == 'debit' ? 'selected' : '' }}>Debit (Money Out)</option>
                        <option value="credit" {{ old('type') == 'credit' ? 'selected' : '' }}>Credit (Money In)</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">Amount *</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-400 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0" required
                            class="w-full pl-7 pr-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-colors @error('amount') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <input type="hidden" name="source" value="manual">
        </div>

        <div class="px-6 py-4 bg-white/[0.03] border-t border-white/10 rounded-b-lg flex items-center justify-end gap-x-3">
            <a href="{{ route('admin.accounting.transactions.index') }}" class="px-4 py-2 text-sm font-medium text-gray-300 bg-[#10231C]/60 border border-white/10 rounded-lg hover:bg-white/[0.03] transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-500 transition-colors">
                Save Record
            </button>
        </div>
    </form>
</div>
@endsection
