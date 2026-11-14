@extends('teamportal::layouts.app')

@section('title', 'Add Bank Account')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="sm:flex-auto">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Add Bank Account</h1>
            <p class="mt-1 text-sm text-gray-400">Enter your bank details to receive payouts.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('team.bank-accounts.index') }}" class="text-sm font-semibold text-gray-400 hover:text-white transition flex items-center gap-1.5">
                &larr; Back to Accounts
            </a>
        </div>
    </div>

    <div class="bg-[#10231C]/60 backdrop-blur-sm shadow-xl border border-white/10 rounded-3xl p-6 sm:p-8">
        @if ($errors->any())
            <div class="rounded-2xl bg-red-950/40 p-4 mb-6 border border-red-500/50 shadow-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-200">There were errors with your submission</h3>
                        <div class="mt-2 text-sm text-red-300">
                            <ul role="list" class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('team.bank-accounts.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="bank_code" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Bank</label>
                    <select id="bank_code" name="bank_code" class="block w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner" required>
                        <option value="" class="bg-[#10231C] text-white">Select a Bank</option>
                        @foreach($banks as $bank)
                            <option value="{{ $bank['code'] ?? $bank['bankCode'] }}" {{ old('bank_code') == ($bank['code'] ?? $bank['bankCode']) ? 'selected' : '' }} class="bg-[#10231C] text-white">
                                {{ $bank['name'] ?? $bank['bankName'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="account_number" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Account Number</label>
                    <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}" class="block w-full rounded-xl border border-white/10 bg-[#08110e]/80 text-white placeholder-gray-500 px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 shadow-inner font-mono" placeholder="0123456789" required maxlength="10">
                    <p class="mt-2 text-xs text-gray-400">Must be a 10-digit NUBAN account number.</p>
                </div>

                <div class="pt-4 border-t border-white/10">
                    <button type="submit" class="inline-flex justify-center items-center rounded-xl bg-emerald-600 hover:bg-emerald-500 py-3.5 px-6 text-sm font-bold text-white shadow-lg shadow-emerald-950/50 w-full transition-all active:scale-95">
                        Verify and Add Account
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
