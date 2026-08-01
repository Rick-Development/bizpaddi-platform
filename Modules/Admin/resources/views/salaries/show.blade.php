@extends('admin::layouts.app')

@section('title', 'Salary Disbursement Details')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Payment Details</h1>
        <p class="mt-1 text-sm text-gray-600">Reference: {{ $payment->reference }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.accounting.salaries.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to List
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-semibold text-gray-900">Transaction Information</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Beneficiary Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $payment->account_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Team Member</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $payment->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="mt-1 text-lg text-brand-primary font-black">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @if($payment->status === 'completed' || $payment->status === 'Successful' || $payment->status === 'processing')
                                <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-xs font-bold text-green-700 ring-1 ring-inset ring-green-600/20 uppercase">
                                    {{ $payment->status }}
                                </span>
                            @elseif($payment->status === 'failed')
                                <span class="inline-flex items-center rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700 ring-1 ring-inset ring-red-600/20 uppercase">
                                    Failed
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-yellow-50 px-3 py-1 text-xs font-bold text-yellow-700 ring-1 ring-inset ring-yellow-600/20 uppercase">
                                    {{ $payment->status }}
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Initiated At</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $payment->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $payment->description ?: 'No description provided.' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-semibold text-gray-900">Bank Details</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-8">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Bank Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $payment->bank_name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Bank Code</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $payment->bank_code }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $payment->account_number }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Session ID (SafeHaven)</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $payment->session_id }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        <div class="bg-gray-900 rounded-2xl p-6 text-white shadow-xl shadow-brand-primary/10">
            <h4 class="text-sm font-bold uppercase tracking-widest text-brand-primary mb-4">Quick Stats</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-4 border-b border-white/10">
                    <span class="text-gray-400 text-xs">Total for user</span>
                    <span class="font-bold">₦ {{ number_format(\Modules\Admin\Models\SalaryDisbursement::where('user_id', $payment->user_id)->sum('amount'), 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-xs">Total Payments</span>
                    <span class="font-bold">{{ \Modules\Admin\Models\SalaryDisbursement::where('user_id', $payment->user_id)->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
