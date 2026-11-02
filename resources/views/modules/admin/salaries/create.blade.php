@extends('admin::layouts.app')

@section('title', 'Process Salary Disbursement')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8" x-data="salaryForm()">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-white sm:truncate sm:text-3xl sm:tracking-tight">Process Salary</h2>
            <p class="mt-2 text-sm text-gray-400">Initiate a transfer to a team member via Direct Transfer.</p>
        </div>
    </div>

    @if(session('error'))
    <div class="rounded-md bg-rose-500/20 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-rose-400">Processing Error</h3>
                <div class="mt-2 text-sm text-rose-400">
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.accounting.salaries.store') }}" method="POST" class="bg-[#10231C]/60 backdrop-blur-xl shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl p-8" @submit="isLoading = true">
        @csrf
        <div class="space-y-6">
            
            <!-- User Selection -->
            <div>
                <label for="user_id" class="block text-sm font-medium leading-6 text-white">Select Team Member</label>
                <div class="mt-2">
                    <select id="user_id" name="user_id" x-model="selectedUserId" class="block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 bg-[#0a1612] text-white border-white/10" required>
                        <option value="">Choose a team member...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <!-- Bank Details Hint -->
                <div class="mt-2 p-3 bg-blue-500/20 rounded-lg border border-white/10" x-show="selectedUser" style="display: none;">
                    <h4 class="text-xs font-semibold text-blue-900 uppercase">Beneficiary Account</h4>
                    <p class="text-sm text-blue-400 mt-1" x-text="bankDetailsText"></p>
                </div>
                <p class="mt-2 text-sm text-gray-400" x-show="!selectedUser">Only users with linked bank accounts are listed.</p>
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium leading-6 text-white">Amount (NGN)</label>
                <div class="relative mt-2 rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="text-gray-400 sm:text-sm">₦</span>
                    </div>
                    <input type="number" name="amount" id="amount" value="{{ request('amount') }}" step="0.01" class="block w-full rounded-md border-0 py-1.5 pl-7 pr-12 text-white ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 bg-[#0a1612] text-white border-white/10" placeholder="0.00" required>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <span class="text-gray-400 sm:text-sm">NGN</span>
                    </div>
                </div>
            </div>

            <!-- Payment Date -->
            <div>
                <label for="payment_date" class="block text-sm font-medium leading-6 text-white">Payment Date</label>
                <div class="mt-2">
                    <input type="date" name="payment_date" id="payment_date" value="{{ date('Y-m-d') }}" class="block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 bg-[#0a1612] text-white border-white/10" required>
                </div>
            </div>

            <!-- Description -->
             <div>
                <label for="description" class="block text-sm font-medium leading-6 text-white">Description / Note</label>
                <div class="mt-2">
                    <textarea rows="2" name="description" id="description" class="block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6" placeholder="e.g. December Salary"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-x-6 pt-4 border-t border-white/10">
                <a href="{{ route('admin.accounting.salaries.index') }}" class="text-sm font-semibold leading-6 text-white">Cancel</a>
                <button type="submit" 
                        class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="isLoading">
                    <span x-show="!isLoading">Process Payment</span>
                    <span x-show="isLoading" class="flex items-center gap-2" style="display: none;">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function salaryForm() {
        return {
            selectedUserId: '{{ request('user_id', '') }}',
            isLoading: false,
            users: @json($users),
            get selectedUser() {
                return this.users.find(u => u.id == this.selectedUserId);
            },
            get bankDetailsText() {
                const user = this.selectedUser;
                if (!user || !user.bank_accounts || user.bank_accounts.length === 0) return 'No account linked';
                const account = user.bank_accounts[0]; // Assuming first or primary
                return `${account.bank_name} - ${account.account_number} (${account.account_name})`;
            }
        }
    }
</script>
@endsection
