<x-home::layouts.master>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-brand-secondary via-brand-primary to-purple-900 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-black opacity-20"></div>
            <div class="absolute top-0 left-0 w-full h-full">
                <div class="absolute top-10 left-10 w-64 h-64 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-40 animate-blob"></div>
                <div class="absolute top-20 right-20 w-64 h-64 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-40 animate-blob animation-delay-2000"></div>
            </div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold text-white sm:text-6xl md:text-7xl">
                    {{ __('About Bizpaddi') }}
                </h1>
                <p class="mt-6 max-w-3xl mx-auto text-xl text-gray-100">
                    {{ __('We are innovators, creators, and problem solvers dedicated to building the future of technology.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-3xl p-10 shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Our Vision') }}</h2>
                    <p class="text-lg text-gray-700">
                        {{ __('To be the leading technology partner that empowers businesses worldwide through innovative digital solutions.') }}
                    </p>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-100 rounded-3xl p-10 shadow-xl">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ __('Our Mission') }}</h2>
                    <p class="text-lg text-gray-700">
                        {{ __('To deliver exceptional technology solutions that drive growth, efficiency, and innovation for our clients.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Leadership Team -->
    @if($leaders->count() > 0)
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-base text-brand-primary font-semibold tracking-wide uppercase">{{ __('Our Team') }}</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    {{ __('Meet Our Leadership') }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($leaders as $leader)
                    <div class="group" x-data="{ expanded: false }">
                        <div class="relative overflow-hidden rounded-2xl shadow-xl transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl cursor-pointer" @click="expanded = !expanded">
                            @if($leader->getFirstMediaUrl('photo'))
                                <img class="w-full h-80 object-cover" src="{{ $leader->getFirstMediaUrl('photo') }}" alt="{{ $leader->name }}">
                            @else
                                <div class="w-full h-80 bg-gradient-to-br from-brand-primary to-purple-600 flex items-center justify-center">
                                    <span class="text-6xl font-bold text-white">{{ substr($leader->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6 flex justify-between items-end">
                                <div>
                                    <h3 class="text-2xl font-bold text-white">{{ $leader->name }}</h3>
                                    <p class="text-yellow-300 font-semibold mt-1">{{ $leader->position }}</p>
                                </div>
                                <div class="text-white bg-white/20 p-2 rounded-full backdrop-blur-sm transform transition-transform" :class="expanded ? 'rotate-180' : ''">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-gray-600 overflow-hidden transition-all duration-300" 
                             :style="expanded ? 'max-height: 500px; opacity: 1;' : 'max-height: 0px; opacity: 0;'">
                            <p class="pt-2">{{ $leader->bio }}</p>
                            @if($leader->linkedin)
                                <a href="{{ $leader->linkedin }}" target="_blank" class="mt-3 inline-flex items-center text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                    LinkedIn Profile
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Timeline -->
    @if($timelines->count() > 0)
    <div class="py-24 relative overflow-hidden bg-slate-50">
        <!-- Background Elements -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-brand-primary/5 rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600/5 rounded-full filter blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20">
                <span class="inline-block py-1 px-3 rounded-full bg-brand-primary/10 text-brand-primary text-sm font-semibold tracking-wider uppercase mb-3">
                    {{ __('Our Story') }}
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-gray-900 via-gray-700 to-gray-900 mb-6">
                    {{ __('The Journey So Far') }}
                </h2>
                <div class="w-24 h-1.5 bg-gradient-to-r from-brand-primary to-purple-600 mx-auto rounded-full"></div>
            </div>

            <!-- Timeline Container -->
            <div class="relative">
                <!-- Vertical Line (Desktop: Center, Mobile: Left) -->
                <div class="absolute left-8 md:left-1/2 top-0 bottom-0 w-0.5 bg-gradient-to-b from-brand-primary via-purple-500 to-transparent transform md:-translate-x-1/2 h-full rounded-full opacity-30"></div>
                
                <div class="space-y-12 md:space-y-0">
                    @foreach($timelines as $index => $timeline)
                        <div class="relative flex flex-col md:flex-row items-center {{ $index % 2 == 0 ? 'md:flex-row-reverse' : '' }}"
                             x-data="{ shown: false }"
                             x-intersect="shown = true">
                            
                            <!-- Spacing for Desktop Center -->
                            <div class="flex-1 w-full md:w-1/2"></div>
                            
                            <!-- Timeline Dot -->
                            <div class="absolute left-8 md:left-1/2 transform -translate-x-1/2 w-8 h-8 rounded-full border-4 border-white bg-gradient-to-br from-brand-primary to-purple-600 shadow-lg z-20 flex items-center justify-center transition-transform duration-500 hover:scale-125"
                                 :class="shown ? 'scale-100 opacity-100' : 'scale-0 opacity-0'">
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            </div>
                            
                            <!-- Content Card -->
                            <div class="flex-1 w-full md:w-1/2 pl-20 md:pl-0 {{ $index % 2 == 0 ? 'md:pr-16 text-left md:text-right' : 'md:pl-16 text-left' }}">
                                <div class="relative group"
                                     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
                                     style="transition: all 0.6s ease-out {{ $index * 0.2 }}s">
                                     
                                    <div class="absolute inset-0 bg-gradient-to-br from-brand-primary/5 to-purple-600/5 rounded-2xl transform transition-transform duration-300 group-hover:scale-105 group-hover:rotate-1"></div>
                                    
                                    <div class="relative bg-white p-8 rounded-2xl shadow-xl border border-gray-100 transition-all duration-300 group-hover:-translate-y-1 group-hover:shadow-2xl">
                                        <div class="inline-block px-4 py-1.5 rounded-full bg-gradient-to-r from-brand-primary to-purple-600 text-white text-sm font-bold shadow-md mb-4 transform transition-transform group-hover:scale-105">
                                            {{ $timeline->year }}
                                        </div>
                                        <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-brand-primary transition-colors">
                                            {{ $timeline->title }}
                                        </h3>
                                        <p class="text-gray-600 leading-relaxed group-hover:text-gray-700">
                                            {{ $timeline->description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Bottom Decoration -->
            <div class="mt-20 text-center">
                 <div class="inline-flex items-center justify-center p-1 rounded-full bg-gradient-to-r from-brand-primary/20 to-purple-600/20 mb-8">
                    <div class="w-3 h-3 bg-gradient-to-r from-brand-primary to-purple-600 rounded-full animate-pulse"></div>
                 </div>
                 <p class="text-gray-500 italic font-medium">{{ __('And the journey continues...') }}</p>
            </div>
        </div>
    </div>
    @endif

    <style>
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</x-home::layouts.master>
