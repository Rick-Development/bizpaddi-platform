<x-home::layouts.master>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-brand-secondary via-brand-primary to-purple-900 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <div class="w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-8 shadow-lg">
                    @if($service->icon)
                        <i class="{{ $service->icon }} text-4xl text-white"></i>
                    @else
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    @endif
                </div>
                <h1 class="text-4xl font-extrabold text-white sm:text-5xl md:text-6xl">
                    {{ $service->name }}
                </h1>
                <p class="mt-6 max-w-3xl mx-auto text-xl text-gray-100">
                    {{ strip_tags($service->description) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg prose-red max-w-none">
                {!! $service->description !!}
            </div>

            @if($service->features && is_array($service->features))
                <div class="mt-12 bg-gray-50 rounded-2xl p-8 shadow-sm border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Key Features & Benefits</h3>
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($service->features as $feature)
                            <li class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-primary/10 flex items-center justify-center mt-1 mr-3">
                                    <svg class="w-4 h-4 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700 font-medium">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Case Studies Section -->
    @if(isset($service->caseStudies) && $service->caseStudies->count() > 0)
    <div class="py-20 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-brand-primary font-semibold tracking-wide uppercase">Proven Results</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    {{ $service->name }} Case Studies
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($service->caseStudies as $caseStudy)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="p-8 border-t-4 border-brand-primary">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $caseStudy->title }}</h3>
                        <p class="text-sm text-brand-primary font-semibold mb-4">{{ $caseStudy->client_name }}</p>
                        
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-1">Challenge</h4>
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $caseStudy->challenge }}</p>
                        </div>
                        
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-1">Results</h4>
                            <p class="text-gray-600 text-sm line-clamp-3">{{ $caseStudy->results }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-brand-primary to-brand-secondary py-16">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                Ready to get started with {{ $service->name }}?
            </h2>
            <p class="mt-4 text-xl text-gray-100">
                Let's discuss how we can tailor this solution to your business needs.
            </p>
            <div class="mt-8 flex justify-center gap-4">
                <a href="/start-project" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-brand-primary bg-white rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                    Start Your Project
                </a>
                <a href="{{ url('/services') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-transparent border-2 border-white rounded-full hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transform hover:-translate-y-1 transition-all duration-200">
                    Back to Services
                </a>
            </div>
        </div>
    </div>
</x-home::layouts.master>
