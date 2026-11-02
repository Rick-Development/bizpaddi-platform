@extends('admin::layouts.app')

@section('title', 'Contact Lists')

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white">Contact Lists</h1>
            <p class="mt-1 sm:mt-2 text-sm text-gray-400">Manage your subscriber lists and segments</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.contacts.export') }}"
               class="inline-flex items-center px-3 py-2 border border-white/10 shadow-sm text-sm font-medium rounded-lg text-gray-300 bg-[#10231C]/60 hover:bg-white/[0.03] transition-colors">
                <i class="fa-solid fa-file-csv mr-2 text-green-600"></i> Export CSV
            </a>
            <a href="{{ route('admin.contacts.export-pdf') }}"
               class="inline-flex items-center px-3 py-2 border border-white/10 shadow-sm text-sm font-medium rounded-lg text-gray-300 bg-[#10231C]/60 hover:bg-white/[0.03] transition-colors">
                <i class="fa-solid fa-file-pdf mr-2 text-red-600"></i> Export PDF
            </a>
            <button type="button" onclick="document.getElementById('import-modal').classList.remove('hidden')"
               class="inline-flex items-center px-3 py-2 border border-white/10 shadow-sm text-sm font-medium rounded-lg text-gray-300 bg-[#10231C]/60 hover:bg-white/[0.03] transition-colors">
                <i class="fa-solid fa-file-import mr-2 text-blue-600"></i> Import CSV
            </button>
            <a href="{{ route('admin.contact-lists.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-[#0A4335] hover:to-[#0E5E4A] shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <i class="fa-solid fa-plus mr-2"></i> New List
            </a>
        </div>
    </div>
</div>

<!-- Desktop View -->
<div class="hidden md:block bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl overflow-x-auto">
    <table class="min-w-full divide-y divide-white/10">
        <thead class="bg-[#10231C]/60/[0.04]">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider w-2/5">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Contacts</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Created</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5 bg-transparent">
            @forelse($lists as $list)
            <tr class="hover:bg-white/[0.03] transition-colors">
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-white">{{ $list->name }}</div>
                    @if($list->description)
                    <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($list->description, 60) }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $list->contacts_count }} contacts
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                    {{ $list->created_at->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($list->is_active)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.contacts.bulk-send-email', ['list_id' => $list->id]) }}"
                           class="text-blue-600 hover:text-blue-800 font-medium">Email</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('admin.contacts.bulk-send-sms', ['list_id' => $list->id]) }}"
                           class="text-green-600 hover:text-green-800 font-medium">SMS</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('admin.contact-lists.show', $list) }}"
                           class="text-emerald-400 hover:text-emerald-300 font-medium">View</a>
                        <a href="{{ route('admin.contact-lists.edit', $list) }}"
                           class="text-gray-400 hover:text-white font-medium">Edit</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                    No contact lists found. Create one to get started.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile View (Cards) -->
<div class="md:hidden space-y-4">
    @forelse($lists as $list)
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-4">
        <div class="flex justify-between items-start mb-2">
            <div class="flex-1 min-w-0 mr-2">
                <h3 class="text-base font-semibold text-white truncate">{{ $list->name }}</h3>
                <p class="text-sm text-gray-400 line-clamp-2 mt-0.5">{{ $list->description }}</p>
            </div>
            @if($list->is_active)
                <span class="flex-shrink-0 px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
            @else
                <span class="flex-shrink-0 px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Inactive</span>
            @endif
        </div>
        
        <div class="flex items-center justify-between mt-3 text-sm text-gray-400">
            <span class="flex items-center">
                <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                {{ $list->contacts_count }} Contacts
            </span>
            <span>{{ $list->created_at->format('M d, Y') }}</span>
        </div>

        <div class="mt-3 flex flex-wrap gap-2 pt-3 border-t border-white/10">
            <a href="{{ route('admin.contacts.bulk-send-email', ['list_id' => $list->id]) }}"
               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full bg-blue-50 text-blue-700 hover:bg-blue-100">
                Email List
            </a>
            <a href="{{ route('admin.contacts.bulk-send-sms', ['list_id' => $list->id]) }}"
               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full bg-green-50 text-green-700 hover:bg-green-100">
                SMS List
            </a>
            <a href="{{ route('admin.contact-lists.show', $list) }}"
               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full text-emerald-400 bg-red-50 hover:bg-red-100">
                View
            </a>
            <a href="{{ route('admin.contact-lists.edit', $list) }}"
               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-full text-gray-400 bg-white/[0.03] hover:bg-white/[0.06]">
                Edit
            </a>
        </div>
    </div>
    @empty
    <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-8 text-center text-gray-400">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        <p class="mt-2 text-sm font-medium text-white">No contact lists found.</p>
        <p class="mt-1 text-sm text-gray-400">Create one to get started.</p>
    </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $lists->links() }}
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
@endsection
