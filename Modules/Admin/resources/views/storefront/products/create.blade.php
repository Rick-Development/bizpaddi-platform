@extends('admin::layouts.app')

@section('title', 'Add Product • Digital Storefront')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-white/10 pb-5">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.storefront.products.index') }}" class="text-xs text-gray-400 hover:text-white transition">&larr; Back to Products</a>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight mt-1">Add New Product</h1>
            <p class="text-xs text-gray-400">Publish a physical or digital product to your public storefront catalog.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.storefront.products.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="p-6 rounded-2xl bg-[#10231C]/40 border border-white/10 space-y-5">
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Product Name *</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="e.g. Enterprise SEO Audit Template" class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500">
                @error('name')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Category</label>
                    <input type="text" name="category" id="category" value="{{ old('category', 'Services') }}" placeholder="e.g. Templates, Software, Consulting" class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500">
                    @error('category')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="stock_quantity" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Stock / Available Units *</label>
                    <input type="number" name="stock_quantity" id="stock_quantity" required min="0" value="{{ old('stock_quantity', 100) }}" class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-emerald-500">
                    @error('stock_quantity')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Regular Price ({{ $tenant->currency ?? 'NGN' }}) *</label>
                    <input type="number" step="0.01" name="price" id="price" required min="0" value="{{ old('price') }}" placeholder="15000.00" class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500">
                    @error('price')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="sale_price" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Discount / Sale Price (Optional)</label>
                    <input type="number" step="0.01" name="sale_price" id="sale_price" min="0" value="{{ old('sale_price') }}" placeholder="Leave blank if not on sale" class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500">
                    @error('sale_price')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Product Description</label>
                <textarea name="description" id="description" rows="4" placeholder="Detail what is included, features, deliverables, or specifications..." class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 leading-relaxed">{{ old('description') }}</textarea>
                @error('description')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Product Image / Mockup</label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0E5E4A] file:text-white hover:file:bg-emerald-600 cursor-pointer">
                <p class="text-[11px] text-gray-400 mt-1">PNG, JPG, or WEBP up to 5MB.</p>
                @error('image')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                    <div class="w-9 h-5 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                    <span class="ml-3 text-xs font-semibold text-gray-300">Publish product immediately on storefront</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.storefront.products.index') }}" class="px-4 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 text-xs font-bold transition">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold transition shadow-md">
                Publish Product
            </button>
        </div>
    </form>
</div>
@endsection
