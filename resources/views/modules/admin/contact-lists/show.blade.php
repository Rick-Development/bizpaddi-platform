@extends('admin::layouts.app')

@section('title', $contactList->name)

@section('content')
<div class="mb-8">
    <div class="sm:flex sm:items-start sm:justify-between gap-4">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-white">{{ $contactList->name }}</h1>
            <p class="mt-2 text-sm text-gray-400">{{ $contactList->description ?? 'No description provided' }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            {{-- Exports --}}
            <a href="{{ route('admin.contacts.export', ['list_id' => $contactList->id]) }}"
               class="inline-flex items-center px-4 py-2 border border-white/10 rounded-lg text-sm font-medium text-gray-300 bg-[#10231C]/60 hover:bg-white/[0.03] shadow-sm transition-colors">
                Export CSV
            </a>
            <a href="{{ route('admin.contacts.export-pdf', ['list_id' => $contactList->id]) }}"
               class="inline-flex items-center px-4 py-2 border border-white/10 rounded-lg text-sm font-medium text-gray-300 bg-[#10231C]/60 hover:bg-white/[0.03] shadow-sm transition-colors">
                Export PDF
            </a>
            
            {{-- Send to entire list --}}
            <a href="{{ route('admin.contacts.bulk-send-email', ['list_id' => $contactList->id]) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-colors">
                Email List
            </a>
            <a href="{{ route('admin.contacts.bulk-send-sms', ['list_id' => $contactList->id]) }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 shadow-sm transition-colors">
                SMS List
            </a>
            
            <button type="button" onclick="document.getElementById('import-modal').classList.remove('hidden')"
               class="inline-flex items-center px-4 py-2 border border-white/10 rounded-lg text-sm font-medium text-gray-300 bg-[#10231C]/60 hover:bg-white/[0.03] shadow-sm transition-colors">
                Import CSV
            </button>
            
            {{-- Actions --}}
            <a href="{{ route('admin.contact-lists.edit', $contactList) }}"
               class="inline-flex items-center px-4 py-2 border border-white/10 rounded-lg text-sm font-medium text-gray-300 bg-[#10231C]/60 hover:bg-white/[0.03]">
                Edit List
            </a>
            <form action="{{ route('admin.contact-lists.destroy', $contactList) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this list? Contacts will not be deleted.');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-white/10 bg-white/[0.03] flex justify-between items-center">
        <h3 class="text-lg font-medium text-white">Contacts in this List ({{ $contacts->total() }})</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-white/10">
            <thead class="bg-[#10231C]/60/[0.04]">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Contact</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email & Phone</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Role & Company</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                    <th scope="col" class="relative px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 bg-transparent">
                @forelse($contacts as $contact)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-white">{{ $contact->full_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-white">{{ $contact->email ?? '—' }}</div>
                        <div class="text-sm text-gray-400">{{ $contact->phone ?? '—' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-white">{{ $contact->role ?? '—' }}</div>
                        <div class="text-xs text-gray-400">{{ $contact->company ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($contact->status == 'active')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-white/[0.06] text-white">{{ ucfirst($contact->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end gap-3">
                            {{-- Send to individual contact --}}
                            @if($contact->email)
                            <a href="{{ route('admin.contacts.send-email', $contact) }}"
                               class="text-blue-600 hover:text-blue-900 font-medium">
                                Email
                            </a>
                            @endif
                            @if($contact->phone)
                            @if($contact->email) <span class="text-gray-300">|</span> @endif
                            <a href="{{ route('admin.contacts.send-sms', $contact) }}"
                               class="text-green-600 hover:text-green-900 font-medium">
                                SMS
                            </a>
                            @endif
                            <span class="text-gray-300">|</span>
                            <form action="{{ route('admin.contact-lists.remove-contact', ['contactList' => $contactList, 'contact' => $contact]) }}"
                                  method="POST" onsubmit="return confirm('Remove contact from this list?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                        No contacts in this list.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">
    {{ $contacts->links() }}
</div>

{{-- Import CSV Modal --}}
<div id="import-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black/50" onclick="document.getElementById('import-modal').classList.add('hidden')"></div>
        <div class="relative bg-[#10231C]/60 rounded-2xl border border-white/10 shadow-xl-xl max-w-md w-full p-6 z-10">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-lg font-bold text-white">Import Contacts to {{ $contactList->name }}</h3>
                <button onclick="document.getElementById('import-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-400">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.contacts.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" name="list_id" value="{{ $contactList->id }}">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">CSV File <span class="text-red-500">*</span></label>
                    <input type="file" name="file" accept=".csv,.txt" required
                           class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-emerald-600/10 file:text-emerald-400 hover:file:bg-emerald-600/20 bg-[#0a1612] text-white border-white/10">
                    <p class="mt-2 text-xs text-gray-400 bg-white/[0.03] rounded p-2">
                        <strong>Required header row:</strong> first_name, last_name, email, phone, company<br>
                        Duplicate emails will be skipped. All added contacts will be added to this list.
                    </p>
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
@endsection
