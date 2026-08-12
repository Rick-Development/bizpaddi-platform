<div class="bg-gray-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-400">
                {{ __('Enterprise-Grade Security & Compliance') }}
            </h3>
        </div>
        <div class="grid grid-cols-2 gap-8 md:grid-cols-4 lg:grid-cols-4 text-center">
            @php
                $trustBadges = \App\Models\TrustBadge::where('is_active', true)->orderBy('sort_order')->get();
            @endphp
            
            @if($trustBadges->count() > 0)
                @foreach($trustBadges as $badge)
                <div class="flex flex-col items-center justify-center space-y-3 p-4 rounded-xl hover:bg-gray-800 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center overflow-hidden">
                        @if($badge->image_path)
                            <img src="{{ asset('storage/' . $badge->image_path) }}" alt="{{ $badge->title }}" class="w-full h-full object-contain p-2">
                        @else
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        @endif
                    </div>
                    <span class="text-sm font-bold text-gray-300">{{ $badge->title }}</span>
                </div>
                @endforeach
            @else
                <!-- Fallback to default if empty (can remove later when DB is populated) -->
                <div class="flex flex-col items-center justify-center space-y-3 p-4 rounded-xl hover:bg-gray-800 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-300">256-bit Encryption</span>
                </div>
                <div class="flex flex-col items-center justify-center space-y-3 p-4 rounded-xl hover:bg-gray-800 transition-colors">
                    <div class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-300">PCI-DSS Ready</span>
                </div>
            @endif
        </div>
    </div>
</div>
