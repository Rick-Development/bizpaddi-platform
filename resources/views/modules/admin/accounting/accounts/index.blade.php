@extends('admin::layouts.app')

@section('title', 'Accounts')

@section('content')
<div class="mb-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-900">Accounts</h1>
            <p class="mt-1 text-sm text-gray-600">Manage chart of accounts and balances</p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('admin.accounting.accounts.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-brand-primary text-white text-base font-semibold rounded-lg hover:bg-brand-primary/90 transition-all shadow-lg hover:shadow-xl">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Account
            </a>
        </div>
    </div>
</div>

@if($accounts->isEmpty())
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
    </svg>
    <h3 class="mt-4 text-lg font-semibold text-gray-900">No accounts yet</h3>
    <p class="mt-2 text-sm text-gray-600">Create your first account to start tracking finances.</p>
    <div class="mt-6">
        <a href="{{ route('admin.accounting.accounts.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Account
        </a>
    </div>
</div>
@else
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full whitespace-nowrap text-left text-sm">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-900">Account</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Category</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Balance</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Transactions</th>
                    <th class="px-6 py-4 font-semibold text-gray-900">Status</th>
                    <th class="px-6 py-4 font-semibold text-gray-900 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @foreach($accounts as $account)
                <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $account->name }}</div>
                        <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $account->code }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($account->category)
                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                            {{ $account->category->name }}
                        </span>
                        @else
                        <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold {{ $account->balance < 0 ? 'text-red-600' : 'text-gray-900' }}">
                        ${{ number_format($account->balance, 2) }}
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ $account->transactions_count }}
                    </td>
                    <td class="px-6 py-4">
                        @if($account->is_active)
                        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Active</span>
                        @else
                        <span class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <form action="{{ route('admin.accounting.accounts.update-balance', $account) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-indigo-400 hover:text-indigo-600 transition-colors" title="Recalculate Balance">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                </button>
                            </form>
                            <a href="{{ route('admin.accounting.accounts.edit', $account) }}" class="text-gray-400 hover:text-brand-primary transition-colors" title="Edit">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.accounting.accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Delete">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $accounts->links() }}
</div>
@endif
@endsection
