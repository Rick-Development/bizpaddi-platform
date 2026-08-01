@extends('admin::layouts.app')

@section('title', 'Bank Transactions')

@section('content')
<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-title-section">
                <h1 class="dashboard-title">
                    <span class="title-accent"></span>
                    <span class="title-text">Bank Transactions</span>
                </h1>
                <p class="dashboard-subtitle">Manage and view your banking transactions</p>
            </div>
            <div class="header-actions">
                <button onclick="window.location.reload()" class="btn-refresh" title="Refresh">
                    <svg class="icon-refresh" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="chart-card">
        <div class="card-header">
            <h3 class="card-title">Transaction History</h3>
        </div>
        
        <!-- Date Filters -->
        <form method="GET" class="filter-form">
            <div class="filter-grid">
                <div class="filter-group">
                    <label for="fromDate">From Date</label>
                    <input type="date" id="fromDate" name="fromDate" value="{{ $fromDate }}" class="filter-input">
                </div>
                <div class="filter-group">
                    <label for="toDate">To Date</label>
                    <input type="date" id="toDate" name="toDate" value="{{ $toDate }}" class="filter-input">
                </div>
                <div class="filter-group">
                    <label for="type">Type</label>
                    <select id="type" name="type" class="filter-input">
                        <option value="">All</option>
                        <option value="Credit" {{ $type == 'Credit' ? 'selected' : '' }}>Credit</option>
                        <option value="Debit" {{ $type == 'Debit' ? 'selected' : '' }}>Debit</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-filter">Apply Filters</button>
                </div>
            </div>
        </form>
        
        <!-- Responsive Table Wrapper -->
        <div class="table-responsive">
            <table class="transactions-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td data-label="Date">
                            <span class="mobile-label">Date:</span>
                            {{ \Carbon\Carbon::parse($transaction['transactionDate'] ?? $transaction['createdAt'] ?? now())->format('M d, Y H:i') }}
                        </td>
                        <td data-label="Reference">
                            <span class="mobile-label">Reference:</span>
                            <span class="font-medium">{{ $transaction['paymentReference'] ?? $transaction['reference'] ?? 'N/A' }}</span>
                        </td>
                        <td data-label="Description">
                            <span class="mobile-label">Description:</span>
                            <span class="truncate-text">{{ $transaction['narration'] ?? $transaction['description'] ?? 'No description' }}</span>
                        </td>
                        <td data-label="Amount">
                            <span class="mobile-label">Amount:</span>
                            <span class="amount {{ ($transaction['type'] ?? '') == 'Credit' ? 'credit' : 'debit' }}">
                                {{ ($transaction['type'] ?? '') == 'Debit' ? '-' : '+' }}₦{{ number_format($transaction['amount'] ?? 0, 2) }}
                            </span>
                        </td>
                        <td data-label="Type">
                            <span class="mobile-label">Type:</span>
                            <span class="badge {{ ($transaction['type'] ?? '') == 'Credit' ? 'badge-success' : 'badge-danger' }}">
                                {{ $transaction['type'] ?? 'Unknown' }}
                            </span>
                        </td>
                        <td data-label="Action">
                            <button onclick='showTransactionDetails(@json($transaction))' class="btn-view">
                                View Details
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <svg class="empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p>No transactions found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if(!empty($meta) && isset($meta['total']) && $meta['total'] > 0)
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Showing {{ count($transactions) }} of {{ $meta['total'] ?? 0 }} transactions
            </div>
            <div class="pagination-controls">
                @php
                    $currentPage = (int)($meta['page'] ?? 0);
                    $totalPages = (int)($meta['pages'] ?? 1);
                @endphp
                
                @if($currentPage > 0)
                    <a href="?page={{ $currentPage }}" class="pagination-btn">
                        <svg style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Previous
                    </a>
                @endif
                
                <span class="pagination-current">Page {{ $currentPage + 1 }} of {{ $totalPages }}</span>
                
                @if($currentPage < $totalPages - 1)
                    <a href="?page={{ $currentPage + 2 }}" class="pagination-btn">
                        Next
                        <svg style="width: 1rem; height: 1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Transaction Details Modal -->
<div id="transactionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Transaction Details</h2>
            <button onclick="closeModal()" class="modal-close">&times;</button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Details will be populated by JavaScript -->
        </div>
    </div>
</div>

<style>
/* Base Styles */
.dashboard-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
    padding: 1.5rem;
}

.dashboard-header {
    background: white;
    border-radius: 1.5rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.dashboard-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.75rem;
    font-weight: 800;
    color: #191C47;
    margin: 0;
}

.title-accent {
    width: 4px;
    height: 2rem;
    background: linear-gradient(180deg, #FB4C4B 0%, #191C47 100%);
    border-radius: 2px;
}

.dashboard-subtitle {
    color: #64748b;
    margin: 0.5rem 0 0 0;
}

.chart-card {
    background: white;
    border-radius: 1.5rem;
    padding: 1.75rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.card-header {
    margin-bottom: 1.5rem;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #191C47;
    margin: 0;
}

.btn-refresh {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.75rem;
    border: none;
    background: #f1f5f9;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-refresh:hover {
    background: #FB4C4B;
    color: white;
}

.icon-refresh {
    width: 1.25rem;
    height: 1.25rem;
}

/* Filter Form */
.filter-form {
    margin-bottom: 1.5rem;
    padding: 1.25rem;
    background: #f9fafb;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-group label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
}

.filter-input {
    padding: 0.625rem 0.875rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    color: #111827;
    background: white;
    transition: all 0.2s;
}

.filter-input:focus {
    outline: none;
    border-color: #191C47;
    box-shadow: 0 0 0 3px rgba(25, 28, 71, 0.1);
}

.btn-filter {
    padding: 0.625rem 1.5rem;
    background: #191C47;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    background: #FB4C4B;
    transform: translateY(-1px);
}

/* Responsive Table */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
}

.transactions-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.transactions-table thead {
    background: #f9fafb;
}

.transactions-table th {
    padding: 1rem 1.5rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 2px solid #e5e7eb;
}

.transactions-table tbody tr {
    border-bottom: 1px solid #f3f4f6;
    transition: background-color 0.2s;
}

.transactions-table tbody tr:hover {
    background-color: #f9fafb;
}

.transactions-table td {
    padding: 1rem 1.5rem;
    font-size: 0.875rem;
    color: #374151;
}

.mobile-label {
    display: none;
}

.truncate-text {
    display: block;
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.amount {
    font-weight: 700;
    font-size: 0.9375rem;
}

.amount.credit {
    color: #10b981;
}

.amount.debit {
    color: #ef4444;
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-success {
    background-color: #d1fae5;
    color: #065f46;
}

.badge-danger {
    background-color: #fee2e2;
    color: #991b1b;
}

.btn-view {
    padding: 0.5rem 1rem;
    background: #191C47;
    color: white;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-view:hover {
    background: #FB4C4B;
    transform: translateY(-1px);
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem !important;
    color: #9ca3af;
}

.empty-icon {
    width: 3rem;
    height: 3rem;
    margin: 0 auto 1rem;
    color: #d1d5db;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
    flex-wrap: wrap;
    gap: 1rem;
}

.pagination-info {
    color: #6b7280;
    font-size: 0.875rem;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.pagination-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    color: #374151;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
}

.pagination-btn:hover {
    background: #f9fafb;
    border-color: #191C47;
    color: #191C47;
}

.pagination-current {
    padding: 0.5rem 1rem;
    color: #6b7280;
    font-size: 0.875rem;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s;
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 1rem;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
    animation: slideDown 0.3s;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #191C47;
}

.modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    color: #9ca3af;
    cursor: pointer;
    line-height: 1;
    padding: 0;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #f3f4f6;
    color: #374151;
}

.modal-body {
    padding: 1.5rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.detail-label {
    font-weight: 600;
    color: #6b7280;
    font-size: 0.875rem;
}

.detail-value {
    color: #111827;
    font-size: 0.875rem;
    text-align: right;
    max-width: 60%;
    word-break: break-word;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .dashboard-title {
        font-size: 1.5rem;
    }
    
    .table-responsive {
        border: none;
    }
    
    .transactions-table {
        min-width: 100%;
    }
    
    .transactions-table thead {
        display: none;
    }
    
    .transactions-table tbody tr {
        display: block;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1rem;
        background: white;
    }
    
    .transactions-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .transactions-table td:last-child {
        border-bottom: none;
    }
    
    .mobile-label {
        display: inline;
        font-weight: 600;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .truncate-text {
        max-width: 200px;
        text-align: right;
    }
    
    .btn-view {
        width: 100%;
    }
    
    .pagination-wrapper {
        flex-direction: column;
        align-items: stretch;
    }
    
    .pagination-controls {
        justify-content: center;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
}
</style>

<script>
function showTransactionDetails(transaction) {
    const modal = document.getElementById('transactionModal');
    const modalBody = document.getElementById('modalBody');
    
    const date = new Date(transaction.transactionDate || transaction.createdAt);
    const formattedDate = date.toLocaleString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit' 
    });
    
    modalBody.innerHTML = `
        <div class="detail-row">
            <span class="detail-label">Transaction Date</span>
            <span class="detail-value">${formattedDate}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Payment Reference</span>
            <span class="detail-value">${transaction.paymentReference || 'N/A'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Type</span>
            <span class="detail-value">
                <span class="badge ${transaction.type === 'Credit' ? 'badge-success' : 'badge-danger'}">
                    ${transaction.type || 'Unknown'}
                </span>
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Amount</span>
            <span class="detail-value">
                <span class="amount ${transaction.type === 'Credit' ? 'credit' : 'debit'}">
                    ${transaction.type === 'Debit' ? '-' : '+'}₦${parseFloat(transaction.amount || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                </span>
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Running Balance</span>
            <span class="detail-value">₦${parseFloat(transaction.runningBalance || 0).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Provider</span>
            <span class="detail-value">${transaction.provider || 'N/A'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Channel</span>
            <span class="detail-value">${transaction.providerChannel || 'N/A'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Payment Service</span>
            <span class="detail-value">${transaction.paymentServices || 'N/A'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Description</span>
            <span class="detail-value">${transaction.narration || 'No description'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Value Date</span>
            <span class="detail-value">${new Date(transaction.valueDate || transaction.transactionDate).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
        </div>
    `;
    
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('transactionModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('transactionModal');
    if (event.target === modal) {
        closeModal();
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection
