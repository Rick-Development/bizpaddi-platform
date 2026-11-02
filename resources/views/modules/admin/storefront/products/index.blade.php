@extends('admin::layouts.app')

@section('title', 'Digital Storefront & Products')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between border-b border-white/10 pb-5">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    Storefront
                </span>
                <span class="text-xs text-gray-400 font-mono">/store/{{ $tenant->slug ?? 'your-store' }}</span>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight mt-1">Digital Storefront & Catalog</h1>
            <p class="text-xs sm:text-sm text-gray-400">Manage your public products, pricing, stock levels, and store configuration.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-3">
            @if($tenant)
            <a href="{{ $tenant->getStorefrontUrl() }}" target="_blank" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-white text-xs font-bold transition">
                <svg class="w-4 h-4 text-[#F2A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>View Live Storefront</span>
            </a>
            @endif
            <a href="{{ route('admin.storefront.settings') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-gray-200 text-xs font-bold transition">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Store Settings</span>
            </a>
            <a href="{{ route('admin.storefront.products.create') }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Product</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-2xl bg-[#10231C]/60 border border-white/10 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-gray-400">Total Products</span>
                <p class="text-2xl font-black text-white mt-1">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-lg">📦</div>
        </div>

        <div class="p-4 rounded-2xl bg-[#10231C]/60 border border-white/10 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-gray-400">Active Online</span>
                <p class="text-2xl font-black text-emerald-400 mt-1">{{ number_format($stats['active']) }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-lg">✅</div>
        </div>

        <div class="p-4 rounded-2xl bg-[#10231C]/60 border border-white/10 flex items-center justify-between">
            <div>
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-gray-400">Out of Stock</span>
                <p class="text-2xl font-black text-amber-400 mt-1">{{ number_format($stats['out_stock']) }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-lg">⚠️</div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="p-4 rounded-2xl bg-[#10231C]/40 border border-white/10">
        <form method="GET" action="{{ route('admin.storefront.products.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products by title, category, description..." class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500">
            </div>

            <div>
                <select name="status" class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-emerald-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'category']))
                <a href="{{ route('admin.storefront.products.index') }}" class="py-2 px-3 rounded-xl bg-white/5 hover:bg-white/10 text-gray-300 text-xs font-bold transition">
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="rounded-2xl bg-[#10231C]/40 border border-white/10 overflow-hidden shadow-sm">
        @if($products->isEmpty())
        <div class="p-12 text-center">
            <div class="w-14 h-14 rounded-full bg-white/5 flex items-center justify-center mx-auto text-2xl mb-3">
                🛍️
            </div>
            <h3 class="text-sm font-bold text-white">No products found</h3>
            <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">Start building your digital storefront by uploading your first product or digital service offering.</p>
            <div class="mt-4">
                <a href="{{ route('admin.storefront.products.create') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Add First Product</span>
                </a>
            </div>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-black/20 text-gray-400 font-extrabold uppercase text-[10px] tracking-wider border-b border-white/10">
                    <tr>
                        <th class="py-3 px-4">Product</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Price</th>
                        <th class="py-3 px-4">Stock</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-gray-300">
                    @foreach($products as $product)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg bg-black/40 overflow-hidden shrink-0 border border-white/10 flex items-center justify-center">
                                    @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                    <span class="text-xs">📦</span>
                                    @endif
                                </div>
                                <div class="truncate max-w-xs">
                                    <p class="font-bold text-white truncate">{{ $product->name }}</p>
                                    <p class="text-[11px] text-gray-400 truncate">{{ $product->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-white/5 text-gray-300 border border-white/10">
                                {{ $product->category ?? 'General' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-white">
                                {{ $tenant->currency ?? 'NGN' }} {{ number_format($product->effective_price, 2) }}
                            </div>
                            @if($product->sale_price && $product->sale_price < $product->price)
                            <div class="text-[10px] text-gray-400 line-through">
                                {{ number_format($product->price, 2) }}
                            </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($product->stock_quantity > 0)
                            <span class="font-semibold text-emerald-400">{{ $product->stock_quantity }} in stock</span>
                            @else
                            <span class="font-semibold text-red-400">Out of stock</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($product->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-500/10 text-gray-400 border border-gray-500/20">
                                Inactive
                            </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.storefront.products.edit', $product->id) }}" class="p-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white transition" title="Edit Product">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.storefront.products.destroy', $product->id) }}" onsubmit="return confirm('Are you sure you want to delete this product?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 transition" title="Delete Product">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-white/5">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
