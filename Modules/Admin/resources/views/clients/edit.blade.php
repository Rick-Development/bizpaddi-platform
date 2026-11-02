@extends('admin::layouts.app')

@section('title', 'Edit Client')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-x-4 border-b border-white/10 pb-5">
        <a href="{{ route('admin.clients.index') }}" class="p-2 rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:text-white hover:bg-white/10 transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Edit Client: {{ $client->name }}</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">Update client profile, credentials, and company billing settings</p>
        </div>
    </div>

    <form action="{{ route('admin.clients.update', $client) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Account Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Details -->
                <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-white mb-4">Account Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('name') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Email (Login) *</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $client->email) }}" required
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('email') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Password</label>
                            <input type="password" name="password" id="password" minlength="8" placeholder="Leave empty to keep current"
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <p class="mt-1 text-[11px] text-gray-400">Only fill if you want to change the password.</p>
                            @error('password') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2 flex items-center mt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $client->is_active) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-white/20 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                <span class="ml-3 text-xs font-bold text-gray-300">Active Account (Portal Access)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Company & Billing -->
                <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-white mb-4">Company & Billing Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="company_name" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Company Name</label>
                            <input type="text" name="company_name" id="company_name" value="{{ old('company_name', $client->company_name) }}"
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone) }}"
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label for="tax_id" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Tax ID / VAT</label>
                            <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $client->tax_id) }}"
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Street Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $client->address) }}"
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label for="city" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">City</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $client->city) }}"
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label for="state" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">State / Province</label>
                            <input type="text" name="state" id="state" value="{{ old('state', $client->state) }}"
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label for="zip" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">ZIP / Postal Code</label>
                            <input type="text" name="zip" id="zip" value="{{ old('zip', $client->zip) }}"
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label for="country" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Country</label>
                            <input type="text" name="country" id="country" value="{{ old('country', $client->country) }}"
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-white mb-4">Additional Info</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="industry" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Industry</label>
                            <input type="text" name="industry" id="industry" value="{{ old('industry', $client->industry) }}" placeholder="e.g. Technology"
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label for="website" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Website</label>
                            <input type="url" name="website" id="website" value="{{ old('website', $client->website) }}" placeholder="https://example.com"
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label for="description" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Internal Notes</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('description', $client->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-xl shadow-lg shadow-emerald-900/30 text-xs font-bold text-white bg-[#0E5E4A] hover:bg-emerald-600 transition-all hover:-translate-y-0.5">
                        Update Client
                    </button>
                    <a href="{{ route('admin.clients.index') }}" class="w-full flex justify-center py-2.5 px-4 border border-white/10 rounded-xl text-xs font-bold text-gray-300 bg-white/5 hover:bg-white/10 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
