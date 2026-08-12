<x-home::layouts.master>
    <div class="relative bg-gradient-to-br from-brand-secondary via-brand-primary to-purple-900 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold text-white sm:text-6xl md:text-7xl">
                    {{ $service->name }}
                </h1>
            </div>
        </div>
    </div>

    <div class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none text-gray-700">
                {!! $service->description !!}
            </div>

            @if($service->features && is_array($service->features))
                <div class="mt-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ __('Key Features') }}</h3>
                    <ul class="space-y-4">
                        @foreach($service->features as $feature)
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-lg text-gray-800">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Security Trust Strip -->
    <x-trust-strip />

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-brand-primary to-purple-600 py-16">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                {{ __('Ready to start your project?') }}
            </h2>
            <div class="mt-8">
                <a href="/start-project" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-brand-primary bg-white rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                    {{ __('Start a Project') }}
                </a>
            </div>
        </div>
    </div>
</x-home::layouts.master>
