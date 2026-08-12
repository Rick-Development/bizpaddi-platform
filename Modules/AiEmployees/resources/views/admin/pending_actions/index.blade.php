@extends('admin::layouts.app')

@section('title', 'AI Pending Actions')

@section('content')
<style>
    #toast-container {
        position: fixed;
        top: 1.5rem;
        right: 1.5rem;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        pointer-events: none;
    }
    .toast-message {
        background: #1e293b;
        border: 1px solid #475569;
        color: #f8fafc;
        padding: 0.875rem 1.25rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.2);
        opacity: 0;
        transform: translateY(-1rem);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: auto;
        min-width: 280px;
    }
    .toast-success { border-color: #10b981; background: #064e3b; color: #d1fae5; }
    .toast-error   { border-color: #ef4444; background: #7f1d1d; color: #fee2e2; }
    .toast-show    { opacity: 1; transform: translateY(0); }
    .row-removing  { transition: opacity 0.35s ease, transform 0.35s ease; opacity: 0; transform: translateX(2rem); }

    /* Bulk action bar transitions */
    #bulk-action-bar {
        transition: transform 0.3s cubic-bezier(0.4,0,0.2,1), opacity 0.3s ease;
    }
    #bulk-action-bar.bar-hidden {
        transform: translateY(6rem);
        opacity: 0;
        pointer-events: none;
    }
    #bulk-action-bar.bar-visible {
        transform: translateY(0);
        opacity: 1;
        pointer-events: auto;
    }

    /* Modal overlay */
    #arguments-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,0.5);
        backdrop-filter: blur(4px);
        z-index: 9000;
        align-items: center;
        justify-content: center;
    }
    #arguments-modal-overlay.modal-open { display: flex; }
</style>

<div id="toast-container"></div>

<!-- Header -->
<div class="relative overflow-hidden rounded-2xl bg-white px-6 py-8 shadow-sm border border-gray-200 mb-8">
    <div class="absolute inset-y-0 left-0 w-1/2 bg-gradient-to-r from-gray-50 to-transparent"></div>
    <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">AI Pending Actions</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600 max-w-xl">
                Review, audit, and bulk authorize autonomous operations queued by your AI Workforce agents.
            </p>
        </div>
        <div class="shrink-0 flex items-center gap-3">
            <a href="{{ route('admin.aiemployees.dashboard') }}" class="inline-flex items-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2.5 text-sm font-semibold transition-all border border-slate-200">
                ← Command Center
            </a>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white border border-slate-200 shadow-sm rounded-2xl overflow-hidden">
    <!-- Card Header -->
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
        <span class="inline-flex items-center gap-1.5 rounded-md bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-800 ring-1 ring-inset ring-amber-600/20">
            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
            <span id="pending-badge">{{ $pendingActions->total() }}</span> pending approval
        </span>
    </div>

    @if($pendingActions->isEmpty())
        <div class="text-center py-20 px-4">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 border border-slate-100 text-slate-300">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-slate-900">All caught up!</h3>
            <p class="mt-1 text-sm text-slate-500">No pending actions require approval right now.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="w-12 px-4 py-3.5">
                            <input type="checkbox" id="select-all"
                                class="h-4 w-4 rounded border-slate-300 cursor-pointer accent-red-500">
                        </th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Agent</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Tool</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Data</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Time</th>
                        <th class="px-4 py-3.5 text-right text-xs font-semibold text-slate-600 uppercase tracking-wide">Decision</th>
                    </tr>
                </thead>
                <tbody id="actions-tbody" class="divide-y divide-slate-100 bg-white">
                    @foreach($pendingActions as $action)
                    <tr class="action-row hover:bg-slate-50/60 transition-colors" data-id="{{ $action->id }}">
                        <td class="w-12 px-4 py-4">
                            <input type="checkbox" class="row-checkbox h-4 w-4 rounded border-slate-300 cursor-pointer accent-red-500" data-id="{{ $action->id }}">
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 shrink-0 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($action->employee->name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900">{{ $action->employee->name ?? 'System Agent' }}</div>
                                    <div class="text-xs text-slate-400">{{ str_replace('_', ' ', \Illuminate\Support\Str::title($action->employee->role ?? '')) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-mono font-medium text-slate-700 ring-1 ring-inset ring-slate-200">
                                {{ $action->tool_name }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <button type="button"
                                class="view-btn inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all"
                                data-args='@json($action->tool_arguments)'>
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                View Data
                            </button>
                        </td>
                        <td class="px-4 py-4 text-sm text-slate-400 whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <button type="button"
                                    class="single-approve-btn rounded-lg px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-all"
                                    data-id="{{ $action->id }}"
                                    data-url="{{ route('admin.aiemployees.dashboard.actions.approve', $action->id) }}">
                                    Approve
                                </button>
                                <button type="button"
                                    class="single-reject-btn rounded-lg px-3 py-1.5 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-all"
                                    data-id="{{ $action->id }}"
                                    data-url="{{ route('admin.aiemployees.dashboard.actions.reject', $action->id) }}">
                                    Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($pendingActions->hasPages())
        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
            {{ $pendingActions->links() }}
        </div>
        @endif
    @endif
</div>

<!-- Floating Bulk Bar -->
<div id="bulk-action-bar" class="bar-hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50 flex items-center gap-4 rounded-full bg-slate-900 px-6 py-3 shadow-2xl border border-slate-700">
    <div class="flex items-center gap-2">
        <span id="bulk-count" class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white text-[10px] font-bold">0</span>
        <span class="text-slate-300 text-xs font-medium">selected</span>
    </div>
    <div class="h-4 w-px bg-slate-600"></div>
    <div class="flex items-center gap-2">
        <button id="bulk-approve-btn" type="button"
            class="flex items-center gap-1.5 rounded-full bg-emerald-600 hover:bg-emerald-500 px-4 py-1.5 text-xs font-bold text-white transition-colors">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            Approve All Selected
        </button>
        <button id="bulk-reject-btn" type="button"
            class="flex items-center gap-1.5 rounded-full bg-rose-600 hover:bg-rose-500 px-4 py-1.5 text-xs font-bold text-white transition-colors">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            Reject All Selected
        </button>
    </div>
</div>

<!-- Arguments Modal -->
<div id="arguments-modal-overlay">
    <div class="relative mx-4 w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-200 overflow-hidden">
        <div class="flex items-center justify-between bg-slate-50 px-6 py-4 border-b border-slate-200">
            <h3 class="text-sm font-bold text-slate-900">Tool Parameters</h3>
            <button id="modal-close" type="button" class="text-slate-400 hover:text-slate-700 transition-colors">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <pre id="modal-content" class="overflow-auto max-h-80 rounded-xl bg-slate-900 p-4 text-xs font-mono text-slate-300 leading-relaxed whitespace-pre-wrap"></pre>
        </div>
        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex justify-end">
            <button id="modal-close-btn" type="button" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition-all shadow-sm">
                Close
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    // ─── CSRF Token ────────────────────────────────────────────────
    // Use Blade-injected token as primary, meta tag as fallback
    const csrfToken = '{{ csrf_token() }}'
        || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || '';

    // ─── Toast ─────────────────────────────────────────────────────
    function showToast(message, type) {
        const container = document.getElementById('toast-container');
        const el = document.createElement('div');
        el.className = 'toast-message ' + (type === 'success' ? 'toast-success' : 'toast-error');
        el.textContent = message;
        container.appendChild(el);
        requestAnimationFrame(() => {
            requestAnimationFrame(() => el.classList.add('toast-show'));
        });
        setTimeout(() => {
            el.classList.remove('toast-show');
            setTimeout(() => el.remove(), 350);
        }, 3800);
    }

    // ─── Modal ─────────────────────────────────────────────────────
    const modalOverlay = document.getElementById('arguments-modal-overlay');
    const modalContent = document.getElementById('modal-content');

    function openModal(data) {
        try {
            const parsed = (typeof data === 'string') ? JSON.parse(data) : data;
            modalContent.textContent = JSON.stringify(parsed, null, 2);
        } catch(e) {
            modalContent.textContent = String(data);
        }
        modalOverlay.classList.add('modal-open');
    }

    function closeModal() {
        modalOverlay.classList.remove('modal-open');
    }

    document.getElementById('modal-close').addEventListener('click', closeModal);
    document.getElementById('modal-close-btn').addEventListener('click', closeModal);
    modalOverlay.addEventListener('click', function(e) {
        if (e.target === modalOverlay) closeModal();
    });

    // ─── View Data buttons ─────────────────────────────────────────
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.view-btn');
        if (!btn) return;
        const raw = btn.getAttribute('data-args');
        openModal(raw);
    });

    // ─── Checkbox & Bulk Bar ───────────────────────────────────────
    const selectAll   = document.getElementById('select-all');
    const bulkBar     = document.getElementById('bulk-action-bar');
    const bulkCount   = document.getElementById('bulk-count');

    function getChecked() {
        return Array.from(document.querySelectorAll('.row-checkbox:checked'));
    }

    function getCheckedIds() {
        return getChecked().map(cb => cb.getAttribute('data-id'));
    }

    function updateBulkBar() {
        const count = getChecked().length;
        bulkCount.textContent = count;
        if (count > 0) {
            bulkBar.classList.remove('bar-hidden');
            bulkBar.classList.add('bar-visible');
        } else {
            bulkBar.classList.remove('bar-visible');
            bulkBar.classList.add('bar-hidden');
        }
        // Sync select-all header checkbox state
        const total = document.querySelectorAll('.row-checkbox').length;
        selectAll.checked = count > 0 && count === total;
        selectAll.indeterminate = count > 0 && count < total;
    }

    // Select All toggle
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.checked = selectAll.checked;
        });
        updateBulkBar();
    });

    // Individual checkboxes
    document.addEventListener('change', function(e) {
        if (!e.target.classList.contains('row-checkbox')) return;
        updateBulkBar();
    });

    // ─── Remove row helper ─────────────────────────────────────────
    function removeRow(id) {
        const row = document.querySelector(`.action-row[data-id="${id}"]`);
        if (row) {
            row.classList.add('row-removing');
            setTimeout(() => row.remove(), 400);
        }
    }

    function decrementBadge(count) {
        const badge = document.getElementById('pending-badge');
        if (!badge) return;
        const current = parseInt(badge.textContent, 10) || 0;
        const next = Math.max(0, current - count);
        badge.textContent = next;
        if (next === 0) {
            setTimeout(() => location.reload(), 600);
        }
    }

    // ─── POST helper ───────────────────────────────────────────────
    function postJSON(url, body, onSuccess, onError) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(body)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) onSuccess(data);
            else onError(data.message || 'Unknown error');
        })
        .catch(() => onError('Network error — please try again.'));
    }

    // ─── Single Approve / Reject ───────────────────────────────────
    document.addEventListener('click', function(e) {
        const approveBtn = e.target.closest('.single-approve-btn');
        const rejectBtn  = e.target.closest('.single-reject-btn');
        const btn = approveBtn || rejectBtn;
        if (!btn) return;

        const id  = btn.getAttribute('data-id');
        const url = btn.getAttribute('data-url');
        const label = approveBtn ? 'Approving…' : 'Rejecting…';
        const orig = btn.textContent;

        btn.disabled = true;
        btn.textContent = label;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Done!', 'success');
                removeRow(id);
                decrementBadge(1);
                updateBulkBar();
            } else {
                btn.disabled = false;
                btn.textContent = orig;
                showToast(data.message || 'Error', 'error');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = orig;
            showToast('Network error — please try again.', 'error');
        });
    });

    // ─── Bulk Approve ──────────────────────────────────────────────
    document.getElementById('bulk-approve-btn').addEventListener('click', function() {
        const ids = getCheckedIds();
        if (!ids.length) return;

        const btn = this;
        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.textContent = 'Approving…';

        postJSON(
            "{{ route('admin.aiemployees.pending-actions.bulk-approve') }}",
            { ids: ids },
            function(data) {
                btn.disabled = false;
                btn.innerHTML = orig;
                showToast(data.message || ids.length + ' actions approved!', 'success');
                ids.forEach(id => removeRow(id));
                decrementBadge(ids.length);
                updateBulkBar();
            },
            function(msg) {
                btn.disabled = false;
                btn.innerHTML = orig;
                showToast(msg, 'error');
            }
        );
    });

    // ─── Bulk Reject ───────────────────────────────────────────────
    document.getElementById('bulk-reject-btn').addEventListener('click', function() {
        const ids = getCheckedIds();
        if (!ids.length) return;

        const btn = this;
        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.textContent = 'Rejecting…';

        postJSON(
            "{{ route('admin.aiemployees.pending-actions.bulk-reject') }}",
            { ids: ids },
            function(data) {
                btn.disabled = false;
                btn.innerHTML = orig;
                showToast(data.message || ids.length + ' actions rejected.', 'success');
                ids.forEach(id => removeRow(id));
                decrementBadge(ids.length);
                updateBulkBar();
            },
            function(msg) {
                btn.disabled = false;
                btn.innerHTML = orig;
                showToast(msg, 'error');
            }
        );
    });

})();
</script>
@endsection
