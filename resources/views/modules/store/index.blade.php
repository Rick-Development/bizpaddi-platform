<x-home::layouts.master>
    <!-- Hero Section -->
    <div class="relative bg-brand-secondary py-24 sm:py-32">
        <div class="absolute inset-0 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1556742049-0cfed4f7a07d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80" alt="Store Background" class="w-full h-full object-cover opacity-10">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-secondary to-brand-primary/90 mix-blend-multiply"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                {{ __('Digital Store') }}
            </h1>
            <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-100">
                {{ __('Discover our premium digital products, themes, and templates to accelerate your projects.') }}
            </p>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="bg-gray-50 py-16 sm:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($products && $products->count() > 0)
                <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                    @foreach($products as $product)
                   
                    <a href="{{ $product['item_url'] ?? '#' }}" target="_blank" class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col h-full">
                        <div class="aspect-w-4 aspect-h-3 bg-gray-200 overflow-hidden group-hover:opacity-90">
                            @if(isset($product['media']['thumbnail']))
                                <img src="{{ $product['media']['thumbnail'] }}" alt="{{ $product['name'] }}" class="w-full h-full object-center object-cover transform group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            @if(isset($product['category']))
                                <div class="absolute top-4 right-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/90 text-brand-primary backdrop-blur-sm shadow-sm">
                                        {{ $product['category'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 p-6 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-brand-primary transition-colors">
                                    {{ $product['name'] }}
                                </h3>
                                <div class="text-sm text-gray-500 line-clamp-2 mb-4 prose prose-sm max-w-none">{!! $product['description'] !!}</div>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 mb-1">Price</span>
                                    @if(isset($product['price']['regular']))
                                        <span class="text-lg font-bold text-gray-900">{{ $product['currency'] ?? '$' }}{{ $product['price']['regular'] }}</span>
                                    @else
                                        <span class="text-lg font-bold text-green-600">Free</span>
                                    @endif
                                </div>
                                <span class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-red-700 transition-colors">
                                    View Details
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach

            <div class="text-center mt-12">
                <a href="https://store.rickdevelopment.com" target="_blank" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-brand-primary rounded-full hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-300 shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                    {{ __(' View All Products') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                    <p class="mt-1 text-sm text-gray-500">Check back later for new digital products.</p>
                </div>
            @endif
        </div>
    </div>
</x-home::layouts.master>
