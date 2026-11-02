@extends('admin::layouts.app')

@section('title', 'Add Contact')

@section('content')
<div class="max-w-3xl mx-auto pb-12">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.contacts.index') }}" class="text-xs text-gray-400 hover:text-white transition">&larr; Back to Contacts</a>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight mt-1">Add New Contact</h1>
            <p class="text-xs sm:text-sm text-gray-400">Add a client or lead contact to your database.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.contacts.index') }}" class="px-4 py-2.5 rounded-xl border border-white/10 text-xs font-semibold text-gray-300 hover:bg-white/5 transition">
                Cancel
            </a>
            <button type="submit" form="create-contact-form" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-lg shadow-emerald-900/30 transition-all hover:-translate-y-0.5 flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                Save Contact
            </button>
        </div>
    </div>

    <form id="create-contact-form" action="{{ route('admin.contacts.store') }}" method="POST" class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-6 sm:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="John"
                       class="w-full px-4 py-2.5 border border-white/10 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white placeholder-gray-500 text-sm">
                @error('first_name')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Doe"
                       class="w-full px-4 py-2.5 border border-white/10 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white placeholder-gray-500 text-sm">
                @error('last_name')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="john@company.com"
                   class="w-full px-4 py-2.5 border border-white/10 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white placeholder-gray-500 text-sm">
            @error('email')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+234 800 000 0000"
                       class="w-full px-4 py-2.5 border border-white/10 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white placeholder-gray-500 text-sm">
                @error('phone')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Company</label>
                <input type="text" name="company" value="{{ old('company') }}" placeholder="Acme Ltd"
                       class="w-full px-4 py-2.5 border border-white/10 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white placeholder-gray-500 text-sm">
                @error('company')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Role / Designation</label>
                <input type="text" name="role" value="{{ old('role') }}" placeholder="e.g. Managing Director, Founder"
                       class="w-full px-4 py-2.5 border border-white/10 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white placeholder-gray-500 text-sm">
                @error('role')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-white/10 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-[#0a1612] text-white text-sm">
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="unsubscribed" {{ old('status') == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                    <option value="bounced" {{ old('status') == 'bounced' ? 'selected' : '' }}>Bounced</option>
                </select>
                @error('status')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-2">Subscribe to Contact Lists (Optional)</label>
            <div class="space-y-2 max-h-48 overflow-y-auto border border-white/10 rounded-xl p-3 bg-[#0a1612]">
                @forelse($lists as $list)
                <label class="flex items-center space-x-2.5 p-1 rounded-lg hover:bg-white/5 cursor-pointer">
                    <input type="checkbox" name="list_ids[]" value="{{ $list->id }}" {{ in_array($list->id, old('list_ids', [])) ? 'checked' : '' }} class="rounded border-white/10 text-emerald-500 focus:ring-emerald-500">
                    <span class="text-xs font-medium text-gray-200">{{ $list->name }}</span>
                </label>
                @empty
                    <p class="text-xs text-gray-400 text-center py-3">No active contact lists created yet.</p>
                @endforelse
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-3 pt-4 border-t border-white/10">
            <a href="{{ route('admin.contacts.index') }}" class="px-5 py-2.5 border border-white/10 rounded-xl text-xs font-semibold text-gray-300 hover:bg-white/5 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-900/30 transition-all hover:-translate-y-0.5">
                Save Contact
            </button>
        </div>
    </form>
</div>
@endsection
