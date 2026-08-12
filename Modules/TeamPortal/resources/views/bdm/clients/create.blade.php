@extends('teamportal::layouts.app')

@section('title', 'Add Client')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('team.bdm.clients.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Client</h1>
            <p class="mt-1 text-sm text-gray-600">Create a new client profile with access credentials</p>
        </div>
    </div>
</div>

<form action="{{ route('team.bdm.clients.store') }}" method="POST">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Account Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Basic Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (Login) *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <input type="password" name="password" id="password" required minlength="8"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 flex items-center mt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-[#FB4C4B] focus:ring-[#FB4C4B] border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Active Account
                        </label>
                    </div>
                </div>
            </div>

            <!-- Company & Billing -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Company & Billing Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                        <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    </div>

                    <div>
                        <label for="tax_id" class="block text-sm font-medium text-gray-700 mb-1">Tax ID / VAT</label>
                        <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State / Province</label>
                        <input type="text" name="state" id="state" value="{{ old('state') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    </div>

                    <div>
                        <label for="zip" class="block text-sm font-medium text-gray-700 mb-1">ZIP / Postal Code</label>
                        <input type="text" name="zip" id="zip" value="{{ old('zip') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    </div>

                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" name="country" id="country" value="{{ old('country') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Optional) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Info</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="industry" class="block text-sm font-medium text-gray-700 mb-1">Industry</label>
                        <input type="text" name="industry" id="industry" value="{{ old('industry') }}" placeholder="e.g. Technology"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <input type="url" name="website" id="website" value="{{ old('website') }}" placeholder="https://example.com"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Internal Notes</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-xl shadow-lg shadow-brand-primary/30 text-sm font-medium text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all hover:-translate-y-0.5">
                    Create Client
                </button>
                <a href="{{ route('team.bdm.clients.index') }}" class="w-full flex justify-center py-2.5 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-colors">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
