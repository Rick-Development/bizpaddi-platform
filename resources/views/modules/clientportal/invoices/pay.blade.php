@extends('clientportal::layouts.app')

@section('title', 'Pay Invoice ' . $invoice->invoice_number)

@section('content')
<div class="mb-6">
    <a href="{{ route('client.invoices.show', $invoice->id) }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
        &larr; Back to Invoice
    </a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-8 border-b border-gray-100 bg-gray-50">
            <h2 class="text-2xl font-bold text-gray-900 text-center">Payment Instructions</h2>
            <p class="mt-2 text-center text-gray-600">Please make a transfer to the account below to pay for your invoice.</p>
            
            <!-- Countdown Timer -->
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-500 mb-2">Account expires in:</p>
                <div id="countdown" class="text-3xl font-bold text-red-600">
                    <span id="minutes">15</span>:<span id="seconds">00</span>
                </div>
            </div>
        </div>
        
        <div class="p-8">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 mb-8 text-center">
                <p class="text-sm text-blue-600 font-medium uppercase tracking-wide mb-1">Total Amount Due</p>
                <p class="text-4xl font-bold text-blue-900">
                    {{ $invoice->currency->symbol ?? '$' }}{{ number_format($invoice->total_amount, 2) }}
                </p>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Bank Name</label>
                    <div class="mt-1 text-lg font-semibold text-gray-900">{{ $virtualAccount->bank_name }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Account Number</label>
                    <div class="mt-1 flex items-center gap-3">
                        <span class="text-2xl font-mono font-bold text-gray-900">{{ $virtualAccount->account_number }}</span>
                        <button onclick="copyToClipboard('{{ $virtualAccount->account_number }}')" class="text-sm text-blue-600 hover:text-blue-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500">Account Name</label>
                    <div class="mt-1 text-lg font-semibold text-gray-900">{{ $virtualAccount->account_name }}</div>
                </div>

                @if($virtualAccount->reference)
                <div>
                    <label class="block text-sm font-medium text-gray-500">Payment Reference</label>
                    <div class="mt-1 text-base font-mono text-gray-700">{{ $virtualAccount->reference }}</div>
                    <p class="text-xs text-gray-400 mt-1">Please include this reference in your transfer narration if possible.</p>
                </div>
                @endif
            </div>

            <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500 mb-4">
                    Once you have made the transfer, click the button below to verify your payment.
                </p>
                <form action="{{ route('client.invoices.confirm-payment', $invoice->id) }}" method="POST">
                    @csrf
                    <button type="submit" id="confirmBtn" class="inline-flex justify-center rounded-md border border-transparent bg-brand-primary py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" id="loadingSpinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span id="btnText">I have made the payment</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Countdown Timer
let totalSeconds = {{ $remainingSeconds }}; // Remaining time from backend
const minutesDisplay = document.getElementById('minutes');
const secondsDisplay = document.getElementById('seconds');
const countdownDiv = document.getElementById('countdown');

function updateCountdown() {
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    
    minutesDisplay.textContent = minutes.toString().padStart(2, '0');
    secondsDisplay.textContent = seconds.toString().padStart(2, '0');
    
    if (totalSeconds <= 60) {
        countdownDiv.classList.add('text-red-600');
    }
    
    if (totalSeconds <= 0) {
        countdownDiv.innerHTML = '<span class="text-red-600">Expired</span>';
        clearInterval(countdownInterval);
        alert('Payment window has expired. Please refresh the page to generate a new account.');
    }
    
    totalSeconds--;
}

const countdownInterval = setInterval(updateCountdown, 1000);
updateCountdown();

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Account number copied to clipboard!');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}

// Show loading state on form submit
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.getElementById('confirmBtn');
    const spinner = document.getElementById('loadingSpinner');
    const btnText = document.getElementById('btnText');
    
    btn.disabled = true;
    spinner.classList.remove('hidden');
    btnText.textContent = 'Verifying payment...';
});

// Poll for payment status
function checkPaymentStatus() {
    fetch('{{ route("client.invoices.check-status", $invoice->id) }}')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'completed' || data.status === 'paid') {
                window.location.href = data.redirect_url;
            }
        })
        .catch(error => console.error('Error checking payment status:', error));
}

// Check every 10 seconds
setInterval(checkPaymentStatus, 10000);
</script>
@endsection
