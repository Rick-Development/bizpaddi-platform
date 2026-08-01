<x-home::layouts.master>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-brand-secondary via-brand-primary to-purple-900 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold text-white sm:text-6xl md:text-7xl">
                    {{ __('Our Portfolio') }}
                </h1>
                <p class="mt-6 max-w-3xl mx-auto text-xl text-gray-100">
                    {{ __('Showcasing our expertise across various industries and technologies.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm" x-data="{ activeFilter: 'all' }">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-3">
                <button @click="activeFilter = 'all'" 
                        :class="{ 'bg-gradient-to-r from-brand-primary to-purple-600 text-white shadow-lg': activeFilter === 'all', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeFilter !== 'all' }"
                        class="px-6 py-3 rounded-full text-sm font-bold transition-all duration-200 transform hover:scale-105">
                    {{ __('All Projects') }}
                </button>
                @foreach($industries as $industry)
                    <button @click="activeFilter = '{{ $industry->id }}'" 
                            :class="{ 'bg-gradient-to-r from-brand-primary to-purple-600 text-white shadow-lg': activeFilter === '{{ $industry->id }}', 'bg-gray-100 text-gray-700 hover:bg-gray-200': activeFilter !== '{{ $industry->id }}' }"
                            class="px-6 py-3 rounded-full text-sm font-bold transition-all duration-200 transform hover:scale-105">
                        {{ $industry->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($projects as $project)
                        <div x-show="activeFilter === 'all' || activeFilter === '{{ $project->industry_id }}'" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             class="group bg-white rounded-3xl shadow-xl overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                            <a href="{{ route('portfolio.show', $project->id) }}" class="block">
                                <div class="relative h-64 overflow-hidden">
                                    @if($project->featured_image)
                                        <img class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500" src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->title }}">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-brand-primary to-purple-600 flex items-center justify-center">
                                            <span class="text-6xl font-bold text-white opacity-50">{{ substr($project->title, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    <div class="absolute top-4 right-4">
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-white text-brand-primary shadow-lg">
                                            {{ $project->year }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="p-8">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold text-brand-primary bg-red-50 rounded-full mb-3">
                                        {{ $project->industry->name }}
                                    </span>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-brand-primary transition-colors">
                                        {{ $project->title }}
                                    </h3>
                                    <p class="text-gray-600 mb-6">
                                        {{ Str::limit($project->clean_description, 120) }}
                                    </p>
                                    
                                    @if($project->impact_metrics && is_array($project->impact_metrics))
                                        <div class="pt-6 border-t border-gray-100">
                                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">{{ __('Impact') }}</h4>
                                            <div class="grid grid-cols-2 gap-4">
                                                @foreach(array_slice($project->impact_metrics, 0, 2) as $key => $value)
                                                    <div>
                                                        <div class="text-2xl font-bold text-brand-primary">{{ $value }}</div>
                                                        <div class="text-xs text-gray-500 mt-1">{{ str_replace('_', ' ', ucfirst($key)) }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-home::layouts.master>
