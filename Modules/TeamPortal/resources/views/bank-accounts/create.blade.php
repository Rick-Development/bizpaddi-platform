@extends('teamportal::layouts.app')

@section('title', 'Add Bank Account')

@section('content')
<div class="sm:flex sm:items-center mb-8">
    <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">Add Bank Account</h1>
        <p class="mt-2 text-sm text-gray-700">Enter your bank details to receive payouts.</p>
    </div>
    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <a href="{{ route('team.bank-accounts.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
            &larr; Back to Accounts
        </a>
    </div>
</div>

<div class="max-w-xl">
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                            <div class="mt-2 text-sm text-red-700">
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
                
                <div class="grid grid-cols-1 gap-y-6">
                    <div>
                        <label for="bank_code" class="block text-sm font-medium text-gray-700">Bank</label>
                        <select id="bank_code" name="bank_code" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-brand-primary focus:outline-none focus:ring-brand-primary sm:text-sm" required>
                            <option value="">Select a Bank</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank['code'] ?? $bank['bankCode'] }}" {{ old('bank_code') == ($bank['code'] ?? $bank['bankCode']) ? 'selected' : '' }}>
                                    {{ $bank['name'] ?? $bank['bankName'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                        <div class="mt-1">
                            <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm" placeholder="0123456789" required maxlength="10">
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Must be a 10-digit NUBAN account number.</p>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-brand-primary py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 w-full">
                            Verify and Add Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
