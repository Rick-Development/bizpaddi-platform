@extends('admin::layouts.app')

@section('title', 'Create Account')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.accounting.accounts.index') }}" class="text-gray-400 hover:text-gray-400">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Create Account</h1>
            <p class="mt-1 text-sm text-gray-400">Add a new account to your chart of accounts</p>
        </div>
    </div>
</div>

<div class="max-w-2xl">
    <form action="{{ route('admin.accounting.accounts.store') }}" method="POST" class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl">
        @csrf
        
        <div class="p-6 space-y-6">
            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-300 mb-2">Category *</label>
                <select name="category_id" id="category_id" required
                    class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('category_id') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }} ({{ $category->type }})
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @if($categories->isEmpty())
                <p class="mt-1 text-sm text-orange-600">
                    No categories found. Run migration to seed default categories or create one.
                </p>
                @endif
            </div>

            <!-- Name and Code -->
            <!-- Name and Code -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="col-span-1">
                    <label for="code" class="block text-sm font-medium text-gray-300 mb-2">Code *</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required placeholder="e.g. 1000"
                        class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('code') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="col-span-1 md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Account Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. Cash in Bank"
                        class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('name') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('description') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Initial Balance -->
            <div>
                <label for="balance" class="block text-sm font-medium text-gray-300 mb-2">Opening Balance</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400 sm:text-sm">$</span>
                    </div>
                    <input type="number" name="balance" id="balance" value="{{ old('balance', 0) }}" step="0.01"
                        class="w-full pl-7 pr-4 py-2 border border-white/10 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-colors @error('balance') border-red-500 @enderror bg-[#0a1612] text-white border-white/10">
                </div>
                <p class="mt-1 text-sm text-gray-400">Only set this for new accounts with existing balance.</p>
                @error('balance')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="flex items-center">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="h-4 w-4 text-brand-primary focus:ring-brand-primary border-white/10 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-300">
                    Active
                </label>
            </div>
        </div>

        <div class="px-6 py-4 bg-white/[0.03] border-t border-white/10 rounded-b-lg flex items-center justify-end gap-x-3">
            <a href="{{ route('admin.accounting.accounts.index') }}" class="px-4 py-2 text-sm font-medium text-gray-300 bg-[#10231C]/60 border border-white/10 rounded-lg hover:bg-white/[0.03] transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-brand-primary rounded-lg hover:bg-brand-primary/90 transition-colors">
                Create Account
            </button>
        </div>
    </form>
</div>
@endsection
