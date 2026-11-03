@extends('admin::layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="text-center">
        <a href="{{ route('admin.billing.index') }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">&larr; Back to Subscription Overview</a>
        <h1 class="text-2xl font-extrabold text-white mt-2">Complete Payment</h1>
        <p class="text-xs text-gray-400">Pay your Bizpaddi subscription via direct bank transfer or virtual account.</p>
    </div>

    <!-- Virtual Account Payment Card -->
    <div class="bg-[#10231C]/60 backdrop-blur-xl rounded-3xl p-8 shadow-2xl border border-white/10 text-center space-y-6 relative overflow-hidden text-white" id="payment-card">
        <!-- Top Accent Bar -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 via-[#F2A900] to-emerald-500"></div>

        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Amount Due</span>
            <span class="text-4xl font-extrabold text-white block mt-1">₦{{ number_format($payment->amount, 2) }}</span>
            <span class="text-xs text-emerald-300 font-semibold px-2.5 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 inline-block mt-2">
                {{ $payment->metadata['plan_name'] ?? 'Bizpaddi Subscription' }}
            </span>
        </div>

        @if($vaNumber)
        <!-- Virtual Account Box -->
        <div class="bg-[#0a1612] rounded-2xl p-6 border border-white/10 text-left space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-white/5">
                <span class="text-xs font-bold uppercase text-gray-400">Bank Name</span>
                <span class="text-sm font-extrabold text-white">{{ $bankName }}</span>
            </div>

            <div class="flex items-center justify-between pb-3 border-b border-white/5">
                <span class="text-xs font-bold uppercase text-gray-400">Account Number</span>
                <div class="flex items-center space-x-2">
                    <span class="text-lg font-mono font-black text-emerald-400 tracking-wider select-all" id="va_number">{{ $vaNumber }}</span>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $vaNumber }}'); showCopyToast();"
                            class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-600 text-white hover:bg-emerald-500 transition active:scale-95">
                        Copy
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase text-gray-400">Beneficiary Name</span>
                <span class="text-xs font-semibold text-gray-200 text-right">{{ $accountName }}</span>
            </div>
        </div>

        <!-- Real-Time Auto-Verification Status Banner -->
        <div id="polling-banner" class="flex items-center justify-center space-x-2.5 text-xs font-medium text-emerald-300 py-2.5 px-4 rounded-xl bg-emerald-950/40 border border-emerald-500/20 transition-all">
            <span class="relative flex h-2.5 w-2.5 flex-shrink-0">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
            </span>
            <span id="polling-text">Checking for incoming transfer in background...</span>
        </div>

        <!-- Success Celebration Banner (Initially hidden) -->
        <div id="success-banner" class="hidden p-5 rounded-2xl bg-emerald-500/20 border border-emerald-400 text-emerald-200 space-y-2 animate-bounce">
            <div class="flex items-center justify-center space-x-2 text-base font-extrabold text-emerald-300">
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>Payment Verified Successfully!</span>
            </div>
            <p class="text-xs text-emerald-200/80">Funds received. Updating your balance & redirecting to dashboard...</p>
        </div>

        <p class="text-[11px] text-gray-400">Transfer exactly <strong>₦{{ number_format($payment->amount, 2) }}</strong> to the account above. Your subscription / wallet activates automatically upon transfer settlement.</p>
        @else
        <div class="p-4 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-400/30 text-xs">
            Virtual account number generation in progress. Check connection with Billway API keys.
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="space-y-3 pt-2">
            <form id="verify-form" action="{{ route('admin.billing.verify-now') }}" method="POST">
                @csrf
                <input type="hidden" name="reference" value="{{ $payment->reference }}">
                <button type="submit" id="verify-btn" class="w-full py-3.5 rounded-2xl text-sm font-bold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-lg transition flex items-center justify-center space-x-2 active:scale-98">
                    <svg id="btn-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <svg id="btn-spinner" class="hidden w-4 h-4 animate-spin text-[#08110e]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span id="btn-text">I Have Made This Transfer</span>
                </button>
            </form>

            <div id="notice-msg" class="hidden text-xs text-amber-300 bg-amber-500/10 border border-amber-500/20 p-2.5 rounded-xl text-center"></div>

            <span class="block text-[11px] text-gray-400">
                Payment Reference: <span class="font-mono font-bold text-gray-300">{{ $payment->reference }}</span>
            </span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkUrl = "{{ route('admin.billing.check-status') }}?reference={{ $payment->reference }}";
    const redirectUrl = "{{ route('admin.billing.index') }}";
    const pollingBanner = document.getElementById('polling-banner');
    const pollingText = document.getElementById('polling-text');
    const successBanner = document.getElementById('success-banner');
    const verifyForm = document.getElementById('verify-form');
    const verifyBtn = document.getElementById('verify-btn');
    const btnIcon = document.getElementById('btn-icon');
    const btnSpinner = document.getElementById('btn-spinner');
    const btnText = document.getElementById('btn-text');
    const noticeMsg = document.getElementById('notice-msg');

    let pollingInterval = null;
    let isChecking = false;
    let isVerified = false;

    window.showCopyToast = function() {
        if (pollingText) {
            const original = pollingText.textContent;
            pollingText.textContent = 'Account number copied to clipboard!';
            setTimeout(() => { if (!isVerified) pollingText.textContent = original; }, 2500);
        }
    };

    function handleSuccess(data) {
        isVerified = true;
        if (pollingInterval) clearInterval(pollingInterval);

        if (pollingBanner) pollingBanner.classList.add('hidden');
        if (noticeMsg) noticeMsg.classList.add('hidden');
        if (verifyForm) verifyForm.classList.add('hidden');

        if (successBanner) {
            successBanner.classList.remove('hidden');
        }

        setTimeout(function () {
            window.location.href = data.redirect_url || redirectUrl;
        }, 1200);
    }

    async function checkPaymentStatus(showSpinner = false) {
        if (isChecking || isVerified) return;
        isChecking = true;

        if (showSpinner && verifyBtn) {
            verifyBtn.disabled = true;
            if (btnIcon) btnIcon.classList.add('hidden');
            if (btnSpinner) btnSpinner.classList.remove('hidden');
            if (btnText) btnText.textContent = 'Verifying transfer with bank...';
        }

        try {
            const response = await fetch(checkUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success && data.status === 'successful') {
                handleSuccess(data);
                return;
            }

            if (showSpinner) {
                if (noticeMsg) {
                    noticeMsg.textContent = 'Bank settlement is still processing. We will automatically detect your transfer the moment it lands.';
                    noticeMsg.classList.remove('hidden');
                }
            }
        } catch (err) {
            console.warn('Background check encountered network jitter, will retry:', err);
        } finally {
            isChecking = false;
            if (showSpinner && verifyBtn && !isVerified) {
                verifyBtn.disabled = false;
                if (btnIcon) btnIcon.classList.remove('hidden');
                if (btnSpinner) btnSpinner.classList.add('hidden');
                if (btnText) btnText.textContent = 'I Have Made This Transfer';
            }
        }
    }

    // Intercept manual button click to verify via AJAX without full page reload
    if (verifyForm) {
        verifyForm.addEventListener('submit', function (e) {
            e.preventDefault();
            checkPaymentStatus(true);
        });
    }

    // Background AJAX polling every 4 seconds
    pollingInterval = setInterval(function () {
        checkPaymentStatus(false);
    }, 4000);

    // Initial check right after load in case already settled
    setTimeout(function() {
        checkPaymentStatus(false);
    }, 1500);
});
</script>
@endsection
