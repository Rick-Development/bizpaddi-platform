<!DOCTYPE html>
<html lang="en" class="h-full bg-[#08110e]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tenant->settings['store_name'] ?? $tenant->name }} &bull; Digital Storefront</title>
    <meta name="description" content="{{ $tenant->settings['store_description'] ?? 'Explore our curated digital products, offerings, and catalog.' }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-[#08110e] text-gray-200 flex flex-col justify-between selection:bg-[#F2A900] selection:text-[#10231C]">

    <!-- Top Announcement Bar -->
    <header class="border-b border-white/10 bg-[#10231C]/90 backdrop-blur sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#0E5E4A] to-[#10231C] border border-white/10 flex items-center justify-center font-black text-lg text-[#F2A900] shadow-inner">
                    {{ strtoupper(substr($tenant->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-base font-bold text-white tracking-tight leading-tight">
                        {{ $tenant->settings['store_name'] ?? $tenant->name }}
                    </h1>
                    <p class="text-[11px] text-gray-400">
                        {{ $tenant->settings['store_tagline'] ?? 'Official Digital Storefront' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center space-x-4 text-xs font-semibold">
                @if(!empty($tenant->settings['store_email']) || !empty($tenant->email))
                <a href="mailto:{{ $tenant->settings['store_email'] ?? $tenant->email }}" class="hidden sm:inline-flex items-center space-x-1.5 text-gray-300 hover:text-white transition">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>{{ $tenant->settings['store_email'] ?? $tenant->email }}</span>
                </a>
                @endif
                @if(!empty($tenant->settings['store_phone']) || !empty($tenant->phone))
                <a href="tel:{{ $tenant->settings['store_phone'] ?? $tenant->phone }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 hover:bg-emerald-500/20 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span>Contact</span>
                </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        <!-- Hero Banner -->
        <section class="relative bg-gradient-to-b from-[#10231C] to-[#08110e] border-b border-white/5 py-16 px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-4xl mx-auto space-y-4">
        
                <h2 class="text-3xl sm:text-5xl font-black text-white tracking-tight">
                    {{ $tenant->settings['store_name'] ?? $tenant->name }}
                </h2>
                <p class="text-sm sm:text-base text-gray-400 max-w-2xl mx-auto leading-relaxed">
                    {{ $tenant->settings['store_description'] ?? 'Browse our complete catalog of products, solutions, and services. Order online or submit direct inquiries to our team.' }}
                </p>
            </div>
        </section>

        <!-- Product Catalog Section -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
                <div>
                    <h3 class="text-lg font-bold text-white tracking-tight">Products & Offerings</h3>
                    <p class="text-xs text-gray-400">Displaying {{ $products->total() }} item(s) in catalog</p>
                </div>

                @if($categories->count() > 1)
                <div class="flex items-center space-x-2 overflow-x-auto pb-2 w-full sm:w-auto">
                    <a href="{{ url()->current() }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ !request('category') ? 'bg-emerald-500 text-white font-bold' : 'bg-white/5 text-gray-300 hover:bg-white/10' }}">
                        All
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ url()->current() }}?category={{ urlencode($cat) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition whitespace-nowrap {{ request('category') === $cat ? 'bg-emerald-500 text-white font-bold' : 'bg-white/5 text-gray-300 hover:bg-white/10' }}">
                        {{ $cat }}
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            @if($products->isEmpty())
            <div class="p-12 text-center rounded-2xl bg-[#10231C]/30 border border-white/5 max-w-lg mx-auto space-y-4">
                <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mx-auto text-2xl">
                    🛍️
                </div>
                <h4 class="text-base font-bold text-white">Catalog Currently Updating</h4>
                <p class="text-xs text-gray-400">
                    {{ $tenant->name }} is actively stocking this storefront. Please check back shortly or reach out directly.
                </p>
                @if(!empty($tenant->email))
                <a href="mailto:{{ $tenant->email }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold transition shadow-sm">
                    Inquire via Email
                </a>
                @endif
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                <div class="group rounded-2xl bg-[#10231C]/50 border border-white/10 hover:border-emerald-500/40 transition-all duration-300 flex flex-col justify-between overflow-hidden shadow-lg hover:shadow-2xl hover:translate-y-[-2px]" x-data="{ detailsOpen: false }">
                    <!-- Product Image -->
                    <div class="relative aspect-video bg-black/40 overflow-hidden flex items-center justify-center">
                        @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 bg-[#0d1c16]">
                            <svg class="w-10 h-10 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span class="text-[11px] font-semibold text-gray-400">Product Offering</span>
                        </div>
                        @endif

                        @if($product->category)
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wider uppercase bg-black/70 backdrop-blur text-emerald-400 border border-white/10">
                                {{ $product->category }}
                            </span>
                        </div>
                        @endif

                        @if($product->sale_price && $product->sale_price < $product->price)
                        <div class="absolute top-3 right-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-400 text-black shadow-md">
                                Sale
                            </span>
                        </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h4 class="text-base font-bold text-white group-hover:text-emerald-300 transition-colors line-clamp-1">
                                {{ $product->name }}
                            </h4>
                            <p class="text-xs text-gray-400 mt-1.5 line-clamp-2 leading-relaxed">
                                {{ $product->description ?? 'No extra description provided.' }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-white/5 flex items-center justify-between">
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-gray-400">Price</span>
                                <div class="flex items-baseline space-x-2">
                                    <span class="text-lg font-black text-white">
                                        {{ $tenant->currency ?? 'NGN' }} {{ number_format($product->effective_price, 2) }}
                                    </span>
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="text-xs text-gray-500 line-through">
                                        {{ number_format($product->price, 2) }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right">
                                @if($product->isInStock())
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    In Stock ({{ $product->stock_quantity }})
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">
                                    Out of Stock
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- Action Button -->
                        @php
                            $inquiryMessage = urlencode("Hello {$tenant->name}, I am interested in purchasing: {$product->name} (Price: {$tenant->currency} " . number_format($product->effective_price, 2) . ")");
                            $phoneClean = preg_replace('/[^0-9]/', '', $tenant->settings['store_phone'] ?? $tenant->phone ?? '');
                        @endphp
                        <div class="pt-2">
                            @if(!empty($phoneClean))
                            <a href="https://wa.me/{{ $phoneClean }}?text={{ $inquiryMessage }}" target="_blank" class="w-full inline-flex items-center justify-center space-x-2 py-2 px-3 rounded-xl bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold transition shadow-md">
                                <span>Order via WhatsApp</span>
                                <span>&rarr;</span>
                            </a>
                            @else
                            <a href="mailto:{{ $tenant->settings['store_email'] ?? $tenant->email }}?subject={{ urlencode('Product Order: ' . $product->name) }}&body={{ $inquiryMessage }}" class="w-full inline-flex items-center justify-center space-x-2 py-2 px-3 rounded-xl bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold transition shadow-md">
                                <span>Order via Email</span>
                                <span>&rarr;</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @endif
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-white/10 bg-[#10231C]/60 py-8 px-4 sm:px-6 lg:px-8 mt-16">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-400">
            <p>&copy; {{ date('Y') }} <span class="font-bold text-white">{{ $tenant->name }}</span>. All rights reserved.</p>
            <div class="flex items-center space-x-2">
                <span>Storefront managed on</span>
                <a href="{{ url('/') }}" target="_blank" class="font-extrabold text-[#F2A900] hover:underline flex items-center space-x-1">
                    <span>Bizpaddi</span>
                </a>
            </div>
        </div>
    </footer>

</body>
</html>
