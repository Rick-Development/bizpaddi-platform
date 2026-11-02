@extends('admin::layouts.app')

@section('title', 'Edit Account')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.accounting.accounts.index') }}" class="text-gray-400 hover:text-gray-400">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Edit Account</h1>
            <p class="mt-1 text-sm text-gray-400">Update account details</p>
        </div>
    </div>
</div>

<div class="max-w-2xl">
    <form action="{{ route('admin.accounting.accounts.update', $account) }}" method="POST" class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl">
        @csrf
        @method('PUT')
        
        <div class="p-6 space-y-6">
            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-300 mb-2">Category *</label>
                <select name="category_id" id="category_id" required
                    class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('category_id') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $account->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }} ({{ $category->type }})
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name and Code -->
            <!-- Name and Code -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label for="code" class="block text-sm font-medium text-gray-300 mb-2">Code *</label>
                    <input type="text" name="code" id="code" value="{{ old('code', $account->code) }}" required
                        class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="col-span-1 md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Account Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $account->name) }}" required
                        class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('description') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">{{ old('description', $account->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $account->is_active) ? 'checked' : '' }}
                    class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-white/10 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-300">
                    Active
                </label>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg flex items-start gap-3">
                <svg class="h-5 w-5 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <div class="text-sm text-blue-700">
                    <p class="font-medium">Calculated Balance: ${{ number_format($account->balance, 2) }}</p>
                    <p class="mt-1">Creating transactions will automatically update this balance. You can manually recalculate it from the accounts list.</p>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-white/[0.03] border-t border-white/10 rounded-b-lg flex items-center justify-end gap-x-3">
            <a href="{{ route('admin.accounting.accounts.index') }}" class="px-4 py-2 text-sm font-medium text-gray-300 bg-[#10231C]/60 border border-white/10 rounded-lg hover:bg-white/[0.03] transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-brand-primary rounded-lg hover:bg-brand-primary/90 transition-colors">
                Update Account
            </button>
        </div>
    </form>
</div>
@endsection
