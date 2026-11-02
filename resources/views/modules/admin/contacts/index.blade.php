@extends('admin::layouts.app')

@section('title', 'Contacts')

@section('content')
<div class="w-full">
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Contacts</h1>
                <p class="mt-1 sm:mt-2 text-sm text-gray-400">View and manage all your gathered contacts and leads.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.contacts.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                   class="inline-flex items-center px-4 py-2 border border-white/10 shadow-sm text-sm font-medium rounded-xl text-gray-300 bg-[#10231C]/60 hover:text-white hover:bg-white/5 transition-all">
                    <i class="fa-solid fa-file-csv mr-2 text-emerald-400"></i> Export CSV
                </a>
                <a href="{{ route('admin.contacts.export-pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                   class="inline-flex items-center px-4 py-2 border border-rose-500/20 bg-rose-500/10 text-rose-300 hover:bg-rose-500/20 shadow-sm text-sm font-medium rounded-xl transition-all">
                    <i class="fa-solid fa-file-pdf mr-2 text-rose-400"></i> Export PDF
                </a>
                <button type="button" onclick="document.getElementById('import-modal').classList.remove('hidden')"
                   class="inline-flex items-center px-4 py-2 border border-white/10 shadow-sm text-sm font-medium rounded-xl text-gray-300 bg-[#10231C]/60 hover:text-white hover:bg-white/5 transition-all">
                    <i class="fa-solid fa-file-import mr-2 text-emerald-400"></i> Import CSV
                </button>
                <a href="{{ route('admin.contacts.create') }}" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-500 shadow-lg shadow-emerald-900/30 transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fa-solid fa-plus mr-2"></i> Add Contact
                </a>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl overflow-hidden mb-6">
        <div class="p-4 sm:p-6">
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-6 lg:col-span-8 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by name, email, or company..." 
                           class="pl-10 w-full block rounded-lg border-white/10 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm h-11 bg-[#0a1612] text-white border-white/10">
                </div>
                <div class="md:col-span-4 lg:col-span-3">
                    <select name="status" class="w-full block rounded-lg border-white/10 shadow-sm focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm h-11 bg-[#0a1612] text-white border-white/10">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                        <option value="bounced" {{ request('status') == 'bounced' ? 'selected' : '' }}>Bounced</option>
                    </select>
                </div>
                <div class="md:col-span-2 lg:col-span-1">
                    <button type="submit" class="w-full h-11 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl shadow-lg shadow-emerald-900/30 transition-all font-bold text-sm">
                        Apply
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Selection Bar -->
    <div id="selection-bar" class="hidden sticky top-16 z-30 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg mb-6 flex items-center justify-between transition-all">
        <div class="flex items-center gap-4">
            <span class="font-bold text-sm"><span id="selected-count">0</span> contacts selected</span>
            <div class="h-4 w-px bg-[#10231C]/60/30 hidden sm:block"></div>
            <div class="flex items-center gap-2">
                <button onclick="submitBulkAction('email')" class="text-xs font-semibold hover:bg-[#10231C]/60/10 px-3 py-1.5 rounded-md transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-envelope"></i>
                    Bulk Email
                </button>
                <button onclick="submitBulkAction('sms')" class="text-xs font-semibold hover:bg-[#10231C]/60/10 px-3 py-1.5 rounded-md transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-comment-sms"></i>
                    Bulk SMS
                </button>
                <button onclick="confirmBulkDelete()" class="text-xs font-semibold hover:bg-[#10231C]/60/10 px-3 py-1.5 rounded-md transition-colors flex items-center gap-2 text-white">
                    <i class="fa-solid fa-trash-can"></i>
                    Bulk Delete
                </button>
            </div>
        </div>
        <button onclick="deselectAll()" class="text-xs font-bold underline hover:no-underline">Deselect All</button>
    </div>

    <form id="bulk-action-form" method="POST" action="" class="hidden">
        @csrf
        <div id="bulk-id-container"></div>
    </form>

    <!-- Desktop View -->
    <div class="hidden md:block bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-white/10">
                <thead class="bg-[#10231C]/60/[0.04]">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left w-10">
                            <input type="checkbox" id="select-all-desktop" class="rounded border-white/10 text-emerald-400 focus:ring-emerald-500">
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Contact Name & Email</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Role & Company</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Phone</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-32">Status</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-40">Date Joined</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider w-32">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 bg-transparent">
                    @forelse($contacts as $contact)
                    <tr class="hover:bg-white/[0.03] transition-colors contact-row" data-id="{{ $contact->id }}">
                        <td class="px-6 py-4">
                            <input type="checkbox" name="contact_ids[]" value="{{ $contact->id }}" class="contact-checkbox rounded border-white/10 text-emerald-400 focus:ring-emerald-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 rounded-full bg-red-50 text-emerald-400 flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($contact->first_name ?? $contact->email ?? 'C', 0, 1)) }}{{ strtoupper(substr($contact->last_name ?? '', 0, 1)) }}
                                </div>
                                <div class="ml-4 min-w-0">
                                    <div class="text-sm font-semibold text-white truncate">{{ $contact->full_name }}</div>
                                    <div class="text-sm text-gray-400 truncate">{{ $contact->email ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-white truncate max-w-[200px]">{{ $contact->role ?? '—' }}</div>
                            <div class="text-xs text-gray-400">{{ $contact->company ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-white">{{ $contact->phone ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($contact->status == 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span>
                                    Active
                                </span>
                            @elseif($contact->status == 'unsubscribed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/[0.06] text-white">
                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-white/[0.03]0"></span>
                                    Unsubscribed
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span>
                                    Bounced
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">
                            {{ $contact->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex justify-end items-center gap-4">
                                <a href="{{ route('admin.contacts.send-email', $contact) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold transition-colors" title="Send Email">
                                    <i class="fa-solid fa-envelope mr-1.5"></i>
                                    Email
                                </a>
                                <a href="{{ route('admin.contacts.send-sms', $contact) }}" class="inline-flex items-center text-green-600 hover:text-green-800 font-semibold transition-colors" title="Send SMS">
                                    <i class="fa-solid fa-comment-dots mr-1.5"></i>
                                    SMS
                                </a>
                                <a href="{{ route('admin.contacts.edit', $contact) }}" class="inline-flex items-center text-emerald-400 hover:text-emerald-300 font-semibold transition-colors">
                                    <i class="fa-solid fa-pen-to-square mr-1.5"></i>
                                    Edit
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete this contact?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center text-gray-400 hover:text-red-600 font-semibold transition-colors">
                                        <i class="fa-solid fa-trash-can mr-1.5"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-user-slash text-4xl mb-3 block text-gray-300"></i>
                            <p class="text-lg">No contacts found</p>
                            <p class="text-sm">Try adjusting your search or filters</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile view (cards) -->
    <div class="md:hidden space-y-4">
        @forelse($contacts as $contact)
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-5 relative contact-card" data-id="{{ $contact->id }}">
            <div class="absolute top-4 right-4">
                <input type="checkbox" name="contact_ids[]" value="{{ $contact->id }}" class="contact-checkbox rounded border-white/10 text-emerald-400 focus:ring-emerald-500">
            </div>
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-red-50 text-emerald-400 flex items-center justify-center font-bold flex-shrink-0">
                        {{ strtoupper(substr($contact->first_name ?? $contact->email ?? 'C', 0, 1)) }}
                    </div>
                    <div class="ml-3 pr-8">
                        <h3 class="text-base font-semibold text-white">{{ $contact->full_name }}</h3>
                        <p class="text-xs text-gray-400">{{ $contact->email ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-xs mb-4">
                <div>
                    <span class="text-gray-400 block mb-1">Role / Company</span>
                    <span class="text-white font-medium block">{{ $contact->role ?? '—' }}</span>
                    <span class="text-gray-400">{{ $contact->company ?? '' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Status / Phone</span>
                    <div class="mb-1">
                        @if($contact->status == 'active')
                            <span class="px-2 py-1 text-[10px] font-bold uppercase rounded bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 py-1 text-[10px] font-bold uppercase rounded bg-red-100 text-red-800">{{ $contact->status }}</span>
                        @endif
                    </div>
                    <span class="text-gray-400 font-medium">{{ $contact->phone ?? '—' }}</span>
                </div>
            </div>
            <div class="pt-4 border-t border-white/10 flex flex-wrap justify-end gap-x-6 gap-y-2">
                <a href="{{ route('admin.contacts.send-email', $contact) }}" class="text-sm font-semibold text-blue-600 flex items-center">
                    <i class="fa-solid fa-envelope mr-1.5"></i>
                    Email
                </a>
                <a href="{{ route('admin.contacts.send-sms', $contact) }}" class="text-sm font-semibold text-green-600 flex items-center">
                    <i class="fa-solid fa-comment-dots mr-1.5"></i>
                    SMS
                </a>
                <a href="{{ route('admin.contacts.edit', $contact) }}" class="text-sm font-semibold text-emerald-400 flex items-center">
                    <i class="fa-solid fa-pen-to-square mr-1.5"></i>
                    Edit
                </a>
                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete?')" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-sm font-semibold text-gray-400 hover:text-red-600 flex items-center">
                        <i class="fa-solid fa-trash-can mr-1.5"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-8 text-center text-gray-400">
            No contacts found
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $contacts->links() }}
    </div>
</div>

{{-- Import CSV Modal --}}
<div id="import-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50" onclick="document.getElementById('import-modal').classList.add('hidden')"></div>
        <div class="relative bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-xl max-w-md w-full p-6 z-10">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-lg font-bold text-white">Import Contacts from CSV</h3>
                <button onclick="document.getElementById('import-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-400">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.contacts.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">CSV File <span class="text-red-500">*</span></label>
                    <input type="file" name="file" accept=".csv,.txt" required
                           class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-600/10 file:text-emerald-400 hover:file:bg-emerald-600/20 bg-[#0a1612] text-white border-white/10">
                    <p class="mt-2 text-xs text-gray-400 bg-white/[0.03] rounded p-2">
                        <strong>Required header row:</strong> first_name, last_name, email, phone, company<br>
                        Duplicate emails will be skipped automatically.
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Add to Contact List <span class="text-gray-400">(Optional)</span></label>
                    <select name="list_id" class="w-full rounded-lg border-white/10 text-sm focus:ring-emerald-500 focus:border-emerald-500 bg-[#0a1612] text-white border-white/10">
                        <option value="">-- Don't add to any list --</option>
                        @foreach(\Modules\Communications\Models\ContactList::where('is_active', true)->get() as $list)
                            <option value="{{ $list->id }}">{{ $list->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('import-modal').classList.add('hidden')"
                            class="px-4 py-2 border border-white/10 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/[0.03]">Cancel</button>
                    <button type="submit"
                            class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-500">
                        <i class="fa-solid fa-upload mr-1.5"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const checkboxes = document.querySelectorAll('.contact-checkbox');
    const selectAllDesktop = document.getElementById('select-all-desktop');
    const selectionBar = document.getElementById('selection-bar');
    const selectedCountSpan = document.getElementById('selected-count');
    const bulkForm = document.getElementById('bulk-action-form');
    const bulkIdContainer = document.getElementById('bulk-id-container');

    function updateSelectionUI() {
        const selectedCount = document.querySelectorAll('.contact-checkbox:checked').length;
        selectedCountSpan.textContent = selectedCount;
        
        if (selectedCount > 0) {
            selectionBar.classList.remove('hidden');
            selectionBar.classList.add('flex');
        } else {
            selectionBar.classList.add('hidden');
            selectionBar.classList.remove('flex');
        }

        document.querySelectorAll('.contact-row, .contact-card').forEach(item => {
            const cb = item.querySelector('.contact-checkbox');
            if (cb.checked) {
                item.classList.add('bg-blue-50/50');
            } else {
                item.classList.remove('bg-blue-50/50');
            }
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectionUI);
    });

    if (selectAllDesktop) {
        selectAllDesktop.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelectionUI();
        });
    }

    function deselectAll() {
        checkboxes.forEach(cb => cb.checked = false);
        if (selectAllDesktop) selectAllDesktop.checked = false;
        updateSelectionUI();
    }

    function submitBulkAction(type) {
        const selectedIds = Array.from(document.querySelectorAll('.contact-checkbox:checked')).map(cb => cb.value);
        
        if (type === 'email') {
            window.location.href = "{{ route('admin.contacts.bulk-send-email') }}?ids=" + selectedIds.join(',');
        } else if (type === 'sms') {
            window.location.href = "{{ route('admin.contacts.bulk-send-sms') }}?ids=" + selectedIds.join(',');
        }
    }

    function confirmBulkDelete() {
        if (!confirm('Are you sure you want to delete all selected contacts? This cannot be undone.')) return;
        
        const selectedIds = Array.from(document.querySelectorAll('.contact-checkbox:checked')).map(cb => cb.value);
        bulkIdContainer.innerHTML = '';
        
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            bulkIdContainer.appendChild(input);
        });

        bulkForm.action = "{{ route('admin.contacts.bulk-delete') }}";
        bulkForm.submit();
    }
</script>
@endpush
@endsection
