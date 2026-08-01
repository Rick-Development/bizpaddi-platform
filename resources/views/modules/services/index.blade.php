<x-home::layouts.master>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-brand-secondary via-brand-primary to-purple-900 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold text-white sm:text-6xl md:text-7xl">
                    {{ __('Our Services') }}
                </h1>
                <p class="mt-6 max-w-3xl mx-auto text-xl text-gray-100">
                    {{ __('Comprehensive technology solutions tailored to drive your business forward.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
                @forelse($services as $service)
                    <div class="group relative bg-white rounded-3xl shadow-xl overflow-hidden transform transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-brand-primary to-purple-600 rounded-full -mr-16 -mt-16 opacity-10 group-hover:opacity-20 transition-opacity"></div>
                        
                        <div class="relative p-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-brand-primary to-purple-600 rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 transition-transform">
                                @if($service->icon)
                                    <i class="{{ $service->icon }} text-3xl text-white"></i>
                                @else
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                @endif
                            </div>
                            
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-brand-primary transition-colors">
                                {{ $service->name }}
                            </h3>
                            
                            <div class="text-gray-600 mb-6 prose prose-sm max-w-none">
                                {!! $service->description !!}
                            </div>
                            
                            @if($service->features && is_array($service->features))
                                <ul class="space-y-3">
                                    @foreach($service->features as $feature)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 text-green-500 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span class="text-gray-700">{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        
                        <div class="px-8 pb-8">
                            <a href="#" class="inline-flex items-center text-brand-primary font-semibold group-hover:text-purple-600 transition-colors">
                                {{ __('Learn More') }}
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">{{ __('No services available at the moment.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-brand-primary to-purple-600 py-16">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                {{ __('Need a custom solution?') }}
            </h2>
            <p class="mt-4 text-xl text-gray-100">
                {{ __('Let\'s discuss how we can help your business grow.') }}
            </p>
            <div class="mt-8">
                <a href="{{ route('contact.index') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-brand-primary bg-white rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                    {{ __('Get in Touch') }}
                </a>
            </div>
        </div>
    </div>
</x-home::layouts.master>
