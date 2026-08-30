<x-home::layouts.master>
    <!-- Hero Section with Database Background -->
    <!-- Hero Section with Database Background -->
    <div class="relative min-h-screen w-full overflow-hidden bg-gray-900">
        @if($heroSliders && $heroSliders->count() > 0)
        <!-- Dynamic Content Slider -->
        <div x-data="{ currentSlide: 0, slides: {{ $heroSliders->count() }} }" x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides }, 9000)" class="absolute inset-0 w-full h-full">
            @foreach($heroSliders as $index => $slider)
            <div x-show="currentSlide === {{ $index }}" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 transform scale-105"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-500"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="absolute inset-0 w-full h-full">
                
                <!-- Background Image from Database or Default -->
                <div class="absolute inset-0">
                    @if($slider->bg_image)
                        <img src="{{ asset('storage/' . $slider->bg_image) }}" alt="{{ $slider->title }}" class="w-full h-full object-cover opacity-40">
                    @elseif($slider->bg_image) {{-- Fallback logic kept simple --}}
                        <img src="{{ asset('storage/' . $slider->bg_image) }}" alt="{{ $slider->title }}" class="w-full h-full object-cover opacity-40">
                    @else
                        <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80" alt="Technology Background" class="w-full h-full object-cover opacity-40">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-r from-brand-secondary/80 to-brand-primary/80 mix-blend-multiply"></div>
                </div>
                
                <div class="relative h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-4xl mx-auto">
                        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-white mb-6 animate-fade-in drop-shadow-lg">
                            {{ $slider->title }}
                        </h1>
                        <p class="text-lg sm:text-xl md:text-2xl text-gray-100 mb-10 max-w-3xl mx-auto animate-fade-in-delay drop-shadow-md">
                            {{ $slider->description }}
                        </p>
                        @if($slider->button_text && $slider->button_link)
                        <div class="animate-fade-in-delay-2">
                            <a href="{{ $slider->button_link }}" class="inline-flex items-center justify-center px-8 py-4 text-base sm:text-lg font-bold text-white bg-brand-secondary rounded-full hover:bg-white hover:text-brand-secondary transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                {{ $slider->button_text }}
                                <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            
            <!-- Slider Indicators -->
            @if($heroSliders->count() > 10)
            <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
                @foreach($heroSliders as $index => $slider)
                <button @click="currentSlide = {{ $index }}" 
                        :class="{ 'bg-white w-10': currentSlide === {{ $index }}, 'bg-white/40 w-3 hover:bg-white/60': currentSlide !== {{ $index }} }"
                        class="h-3 rounded-full transition-all duration-300"></button>
                @endforeach
            </div>
            @endif
        </div>
        @else
            <!-- Static Fallback if no sliders -->
            <div class="absolute inset-0 w-full h-full">
                <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=2072&q=80" alt="Background" class="w-full h-full object-cover opacity-30">
                <div class="absolute inset-0 bg-gradient-to-r from-brand-secondary/80 to-brand-primary/80"></div>
                <div class="relative h-full flex items-center justify-center">
                    <h1 class="text-white text-5xl font-bold">Welcome to Our Platform</h1>
                </div>
            </div>
        @endif
    </div>

    <!-- Client Logos & Trust Badges -->
    <div class="bg-white py-12 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm font-semibold uppercase text-gray-500 tracking-wider mb-8">
                {{ __('Trusted by innovative companies worldwide') }}
            </p>
            <div class="flex flex-wrap justify-center gap-8 md:gap-16 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                <div class="flex items-center justify-center">
                    <span class="text-2xl font-bold text-gray-400">TechCorp</span>
                </div>
                <div class="flex items-center justify-center">
                    <span class="text-2xl font-bold text-gray-400">Innovate LLC</span>
                </div>
                <div class="flex items-center justify-center">
                    <span class="text-2xl font-bold text-gray-400">GlobalNet</span>
                </div>
                <div class="flex items-center justify-center">
                    <span class="text-2xl font-bold text-gray-400">NextGen Solutions</span>
                </div>
                <div class="flex items-center justify-center">
                    <span class="text-2xl font-bold text-gray-400">Alpha Systems</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Content Section (Service Categories) -->
    @if($serviceCategories && $serviceCategories->count() > 0)
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-brand-primary font-semibold tracking-wide uppercase">{{ __('Featured') }}</h2>
                <p class="mt-2 text-4xl leading-tight font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                    {{ __('What We Offer') }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($serviceCategories as $category)
                    <div class="group relative bg-white rounded-3xl shadow-xl overflow-hidden transform transition-all duration-300 hover:-translate-y-3 hover:shadow-2xl border-2 border-gray-100"
                         style="border-color: transparent;"
                         onmouseover="this.style.borderColor='{{ $category->color ?? '#3B82F6' }}'"
                         onmouseout="this.style.borderColor='transparent'">
                        
                        @if($category->image)
                        <div class="relative h-56 overflow-hidden">
                            @php
                                $catImg = $category->image;
                                $catImg = preg_replace('/^https?:\/\/[^\/]+/', '', $catImg);
                                while (preg_match('/^\/?storage\//i', $catImg)) {
                                    $catImg = preg_replace('/^\/?storage\//i', '', $catImg);
                                }
                                if (preg_match('/https?:\/\/[^\/]+\/(.*)$/i', $catImg, $matches)) {
                                    $catImg = $matches[1];
                                    while (preg_match('/^\/?storage\//i', $catImg)) {
                                        $catImg = preg_replace('/^\/?storage\//i', '', $catImg);
                                    }
                                }
                                $catImg = asset('storage/' . ltrim($catImg, '/'));
                            @endphp
                            <img class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500" src="{{ $catImg }}" alt="{{ $category->name }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        </div>
                        @else
                        <div class="relative h-56 overflow-hidden flex items-center justify-center bg-gray-100">
                             <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        @endif
                        
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-4 transition-colors"
                                style="color: inherit;"
                                onmouseover="this.style.color='{{ $category->color ?? '#3B82F6' }}'"
                                onmouseout="this.style.color='inherit'">
                                {{ $category->name }}
                            </h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">
                                {{ Str::limit($category->description, 100) }}
                            </p>
                            <a href="{{ route('services.index') }}" class="inline-flex items-center font-bold transition-colors"
                               style="color: {{ $category->color ?? '#3B82F6' }}">
                                {{ __('Learn More') }}
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Services Preview -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-brand-primary font-semibold tracking-wide uppercase">{{ __('Our Services') }}</h2>
                <p class="mt-2 text-4xl leading-tight font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                    {{ __('Comprehensive Digital Solutions') }}
                </p>
            </div>

            @if($services && $services->count() > 0)
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($services as $service)
                <div class="group relative bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-t-4 border-brand-primary">
                    <div class="w-14 h-14 bg-gradient-to-br from-brand-primary to-red-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        @if($service->icon)
                            <i class="{{ $service->icon }} text-2xl text-white"></i>
                        @else
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $service->name }}</h3>
                    <p class="text-gray-600 line-clamp-3">{{ strip_tags($service->description) }}</p>
                </div>
                @endforeach
            </div>
            @endif

            <div class="text-center mt-12">
                <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-brand-primary rounded-full hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-300 shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                    {{ __('View All Services') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-gradient-to-r from-brand-secondary to-brand-primary py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                @if(isset($homeStats) && $homeStats->count() > 0)
                    @foreach($homeStats as $stat)
                    <div class="text-center transform hover:scale-110 transition-transform">
                        <div class="text-5xl font-extrabold text-yellow-300">{{ $stat->value }}</div>
                        <div class="mt-3 text-lg font-semibold text-white">{{ $stat->label }}</div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Why Choose Us Section -->
    <div class="py-20 bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-brand-primary font-semibold tracking-wide uppercase">{{ __('Why Choose Us') }}</h2>
                <p class="mt-2 text-4xl leading-tight font-extrabold tracking-tight text-brand-secondary sm:text-5xl">
                    {{ __('We Deliver Excellence') }}
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-600 mx-auto">
                    {{ __('Discover what sets us apart from the competition') }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                @if(isset($homeFeatures) && $homeFeatures->count() > 0)
                    @foreach($homeFeatures as $feature)
                    <div class="group relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-brand-primary to-brand-secondary rounded-2xl opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                        <div class="relative text-center p-8 bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-brand-primary/20">
                            <div class="w-20 h-20 bg-gradient-to-br from-brand-primary to-brand-primary/80 rounded-2xl flex items-center justify-center mx-auto mb-6 transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                                @if($feature->icon)
                                    <i class="{{ $feature->icon }} text-4xl text-white"></i>
                                @else
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-brand-secondary mb-3 group-hover:text-brand-primary transition-colors">{{ $feature->title }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $feature->description }}</p>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Our Process Section -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-brand-primary font-semibold tracking-wide uppercase">{{ __('Our Process') }}</h2>
                <p class="mt-2 text-4xl leading-tight font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                    {{ __('How We Work') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @if(isset($homeProcesses) && $homeProcesses->count() > 0)
                    @foreach($homeProcesses as $index => $process)
                    <div class="relative">
                        <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow">
                            <div class="w-16 h-16 bg-gradient-to-br from-brand-primary to-red-600 rounded-full flex items-center justify-center mb-6">
                                <span class="text-2xl font-bold text-white">{{ $process->step_number }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $process->title }}</h3>
                            <p class="text-gray-600">{{ $process->description }}</p>
                        </div>
                        @if(!$loop->last)
                        <div class="hidden lg:block absolute top-1/2 -right-4 w-8 h-0.5 bg-brand-primary"></div>
                        @endif
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Portfolio Preview Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-brand-primary font-semibold tracking-wide uppercase">{{ __('Recent Work') }}</h2>
                <p class="mt-2 text-4xl leading-tight font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                    {{ __('Featured Projects') }}
                </p>
            </div>

            @if($recentProjects && $recentProjects->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($recentProjects as $project)
                <a href="{{ route('portfolio.show', $project->id) }}" class="group relative bg-white rounded-3xl shadow-xl overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl block">
                    <div class="relative h-64 overflow-hidden">
                        @if($project->featured_image)
                            <img src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-brand-primary to-purple-600 flex items-center justify-center">
                                <svg class="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $project->title }}</h3>
                        <p class="text-gray-600">{!! Str::limit(strip_tags($project->description), 100) !!}</p>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <p class="text-gray-500">{{ __('No projects available at the moment.') }}</p>
            </div>
            @endif

            <div class="text-center">
                <a href="{{ route('portfolio.index') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-brand-primary rounded-full hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-300 shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                    {{ __('View All Projects') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-brand-primary font-semibold tracking-wide uppercase">{{ __('Testimonials') }}</h2>
                <p class="mt-2 text-4xl leading-tight font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                    {{ __('What Our Clients Say') }}
                </p>
            </div>

            @if($testimonials && $testimonials->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($testimonials as $testimonial)
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-2xl transition-shadow">
                    <div class="flex items-center mb-4">
                        @for($j = 0; $j < 5; $j++)
                        <svg class="w-5 h-5 {{ $j < ($testimonial->rating ?? 5) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        @endfor
                    </div>
                    <p class="text-gray-600 mb-6 italic">"{{ $testimonial->message }}"</p>
                    <div class="flex items-center">
                        @if($testimonial->getFirstMediaUrl('avatar'))
                            <img src="{{ $testimonial->getFirstMediaUrl('avatar') }}" alt="{{ $testimonial->client_name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-brand-primary to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($testimonial->client_name, 0, 1) }}
                            </div>
                        @endif
                        <div class="ml-4">
                            <p class="font-bold text-gray-900">{{ $testimonial->client_name }}</p>
                            <p class="text-sm text-gray-500">{{ $testimonial->client_position ?? 'Client' }}{{ $testimonial->company_name ? ', ' . $testimonial->company_name : '' }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <p class="text-gray-500">{{ __('No testimonials available at the moment.') }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Latest Blog Posts -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-brand-primary font-semibold tracking-wide uppercase">{{ __('Latest Insights') }}</h2>
                <p class="mt-2 text-4xl leading-tight font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                    {{ __('From Our Blog') }}
                </p>
            </div>

            @if($latestPosts && $latestPosts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                @foreach($latestPosts as $post)
                <a href="{{ route('blog.show', $post->id) }}" class="block">
                <article class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow">
                    @if($post->featured_image)
                        <div class="h-48 overflow-hidden">
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600"></div>
                    @endif
                    <div class="p-6">
                        @if($post->category)
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-brand-primary bg-red-50 rounded-full mb-3">
                            {{ $post->category->name }}
                        </span>
                        @endif
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $post->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit(strip_tags($post->content), 120) }}</p>
                        <div class="flex items-center text-sm text-gray-500">
                            <time>{{ $post->published_at->format('M d, Y') }}</time>
                            <span class="mx-2">•</span>
                            <span>{{ $post->read_time ?? '5' }} min read</span>
                        </div>
                    </div>
                </article>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <p class="text-gray-500">{{ __('No blog posts available at the moment.') }}</p>
            </div>
            @endif

            <div class="text-center">
                <a href="{{ route('blog.index') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-brand-primary bg-white border-2 border-brand-primary rounded-full hover:bg-brand-primary hover:text-white focus:outline-none focus:ring-4 focus:ring-red-300 shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                    {{ __('Read More Articles') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Store Products Section -->
    @if($storeProducts && $storeProducts->count() > 0)
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-brand-primary font-semibold tracking-wide uppercase">{{ __('Digital Store') }}</h2>
                <p class="mt-2 text-4xl leading-tight font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                    {{ __('Premium Resources') }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @foreach($storeProducts as $product)
                <a href="{{ $product['item_url'] ?? $product['demo_link'] ?? '#' }}" target="_blank" class="group relative bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col h-full">
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
                                View
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="https://store.rickdevelopment.com" target="_blank" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white bg-brand-primary rounded-full hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-300 shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                    {{ __('Visit Store') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- FAQ Section -->
    <div class="bg-white py-20 border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-brand-primary font-semibold tracking-wide uppercase">{{ __('FAQ') }}</h2>
                <p class="mt-2 text-4xl leading-tight font-extrabold tracking-tight text-gray-900 sm:text-5xl">
                    {{ __('Common Questions') }}
                </p>
                <p class="mt-4 text-xl text-gray-600">Everything you need to know about working with us.</p>
            </div>
            
            <div class="space-y-6" x-data="{ active: null }">
                <!-- FAQ 1 -->
                <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
                    <button class="w-full px-6 py-5 text-left font-bold text-gray-900 flex justify-between items-center focus:outline-none" @click="active = active === 1 ? null : 1">
                        <span>How much does a typical project cost?</span>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="px-6 pb-5 text-gray-600 border-t border-gray-100" x-show="active === 1" x-collapse x-cloak>
                        <p class="pt-4">Project costs vary widely depending on scope, complexity, and timeline. After our initial free consultation, we provide a detailed proposal with transparent pricing tailored to your specific needs. We offer options for both fixed-price projects and dedicated team retainers.</p>
                    </div>
                </div>
                
                <!-- FAQ 2 -->
                <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
                    <button class="w-full px-6 py-5 text-left font-bold text-gray-900 flex justify-between items-center focus:outline-none" @click="active = active === 2 ? null : 2">
                        <span>How long does it take to complete a project?</span>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="px-6 pb-5 text-gray-600 border-t border-gray-100" x-show="active === 2" x-collapse x-cloak>
                        <p class="pt-4">Timelines depend entirely on the project scope. A simple landing page might take a few weeks, while a complex custom web application could take 3-6 months. We emphasize agile methodologies, meaning we deliver working software in sprints so you see progress early and often.</p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
                    <button class="w-full px-6 py-5 text-left font-bold text-gray-900 flex justify-between items-center focus:outline-none" @click="active = active === 3 ? null : 3">
                        <span>Do you provide post-launch support and maintenance?</span>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300" :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="px-6 pb-5 text-gray-600 border-t border-gray-100" x-show="active === 3" x-collapse x-cloak>
                        <p class="pt-4">Yes! We believe launch is just the beginning. We offer flexible post-launch support and maintenance retainers to ensure your application remains secure, up-to-date, and optimized as your business grows.</p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="border border-gray-200 rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
                    <button class="w-full px-6 py-5 text-left font-bold text-gray-900 flex justify-between items-center focus:outline-none" @click="active = active === 4 ? null : 4">
                        <span>What is your development process like?</span>
                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-300" :class="active === 4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="px-6 pb-5 text-gray-600 border-t border-gray-100" x-show="active === 4" x-collapse x-cloak>
                        <p class="pt-4">We follow a transparent, agile process: Discovery & Strategy -> UI/UX Design -> Development (in sprints) -> QA & Testing -> Launch. You'll have regular check-ins, access to a staging environment, and a dedicated project manager keeping you in the loop every step of the way.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    @if($homeCta)
    <div class="bg-white py-20">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
                {{ $homeCta->title }}
            </h2>
            <p class="mt-6 text-xl text-gray-600 leading-relaxed">
                {{ $homeCta->description }}
            </p>
            <div class="mt-10 flex justify-center gap-4">
                @if($homeCta->button1_text && $homeCta->button1_link)
                <a href="{{ $homeCta->button1_link }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white bg-gradient-to-r from-brand-primary to-red-600 rounded-full hover:from-red-600 hover:to-brand-primary focus:outline-none focus:ring-4 focus:ring-red-300 shadow-2xl transform hover:-translate-y-1 transition-all duration-200">
                    {{ $homeCta->button1_text }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
                @endif
                @if($homeCta->button2_text && $homeCta->button2_link)
                <a href="{{ $homeCta->button2_link }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-brand-primary bg-white border-2 border-brand-primary rounded-full hover:bg-brand-primary hover:text-white focus:outline-none focus:ring-4 focus:ring-red-300 shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                    {{ $homeCta->button2_text }}
                </a>
                @endif
            </div>
        </div>
    </div>
    @endif

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }
        .animate-fade-in-delay {
            animation: fadeIn 1s ease-out 0.3s both;
        }
        .animate-fade-in-delay-2 {
            animation: fadeIn 1s ease-out 0.6s both;
        }
    </style>
</x-home::layouts.master>
