@extends('teamportal::layouts.app')

@section('title', 'My Bank Account')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Bank Account</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your bank account for salary and payouts</p>
        </div>
    </div>

    @if($account)
    <!-- Bank Account Card -->
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-2xl overflow-hidden">
        <div class="px-6 py-8">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-xl bg-brand-primary/10 flex items-center justify-center">
                        <svg class="h-7 w-7 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">{{ $account->bank_name }}</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Primary account for payouts</p>
                    </div>
                </div>
                <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">
                    Active
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Account Name</label>
                    <p class="text-base font-medium text-gray-900">{{ $account->account_name }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Account Number</label>
                    <p class="text-base font-medium text-gray-900 font-mono">{{ $account->account_number }}</p>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-3">
                <form action="{{ route('team.bank-accounts.destroy', $account) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this bank account? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-2.5 text-sm font-semibold text-red-700 shadow-sm hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                        Delete Account
                    </button>
                </form>
                <p class="text-xs text-gray-500 sm:hidden text-center">To change your bank account, delete this one and add a new account.</p>
            </div>
        </div>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-blue-700">
                    <strong>Note:</strong> To update your bank account details, you'll need to delete this account and add a new one. This ensures all information is properly verified.
                </p>
            </div>
        </div>
    </div>

    @else
    <!-- Empty State -->
    <div class="text-center py-12 bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5">
        <div class="mx-auto h-16 w-16 rounded-xl bg-brand-primary/10 flex items-center justify-center mb-4">
            <svg class="h-8 w-8 text-brand-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Bank Account Added</h3>
        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">Add your bank account to receive salary payments and other disbursements.</p>
        <a href="{{ route('team.bank-accounts.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-brand-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 transition-colors">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Bank Account
        </a>
    </div>
    @endif
</div>
@endsection
