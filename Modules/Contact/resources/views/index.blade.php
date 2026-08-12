<x-home::layouts.master>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-brand-secondary via-brand-primary to-purple-900 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold text-white sm:text-6xl md:text-7xl">
                    {{ __('Get in Touch') }}
                </h1>
                <p class="mt-6 max-w-3xl mx-auto text-xl text-gray-100">
                    {{ __('Have a project in mind? Let\'s talk about how we can help you achieve your goals.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div class="bg-gradient-to-br from-brand-primary to-purple-600 rounded-3xl p-12 text-white shadow-2xl">
                    <h2 class="text-3xl font-bold mb-8">{{ __('Contact Information') }}</h2>
                    
                    <div class="space-y-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-white/80">{{ __('Phone') }}</p>
                                <p class="text-lg font-bold">{{ \Modules\Settings\Models\Setting::get('contact_phone', '+1 (555) 123-4567') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-white/80">{{ __('Email') }}</p>
                                <p class="text-lg font-bold">{{ \Modules\Settings\Models\Setting::get('contact_email', 'info@rickdevelopment.com') }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-semibold text-white/80">{{ __('Address') }}</p>
                                <p class="text-lg font-bold">{!! nl2br(e(\Modules\Settings\Models\Setting::get('contact_address', "123 Tech Avenue\nSilicon Valley, CA"))) !!}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 pt-8 border-t border-white/20">
                        <p class="text-white/80 mb-4">{{ __('Follow us on social media') }}</p>
                        <div class="flex space-x-4">
                            @php
                                $facebook = \Modules\Settings\Models\Setting::get('social_facebook');
                                $twitter = \Modules\Settings\Models\Setting::get('social_twitter');
                                $linkedin = \Modules\Settings\Models\Setting::get('social_linkedin');
                                $instagram = \Modules\Settings\Models\Setting::get('social_instagram');
                            @endphp

                            @if($facebook)
                            <a href="{{ $facebook }}" target="_blank" class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            @endif

                            @if($twitter)
                            <a href="{{ $twitter }}" target="_blank" class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932 6.064-6.932zm-1.294 19.497h2.039L6.486 3.24H4.298l13.309 17.41z"/>
                                </svg>
                            </a>
                            @endif

                            @if($linkedin)
                            <a href="{{ $linkedin }}" target="_blank" class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                            @endif
                            
                            @if($instagram)
                            <a href="{{ $instagram }}" target="_blank" class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.584-.071 4.85c-.055 1.17-.249 1.805-.415 2.227-.217.562-.477.96-.896 1.382-.42.419-.819.679-1.381.896-.422.164-1.056.36-2.227.413-1.266.057-1.646.07-4.85.07s-3.584-.015-4.85-.071c-1.17-.055-1.805-.249-2.227-.415-.562-.217-.96-.477-1.382-.896-.419-.42-.679-.819-.896-1.381-.164-.422-.36-1.057-.413-2.227-.057-1.266-.07-1.646-.07-4.85s.015-3.584.071-4.85c.055-1.17.249-1.805.415-2.227.217-.562.477-.96.896-1.382.42-.419.819-.679 1.381-.896.422-.164 1.057-.36 2.227-.413 1.266-.057 1.646-.07 4.85-.07zM12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white rounded-3xl p-12 shadow-2xl">
                    @if(session('success'))
                        <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <h2 class="text-3xl font-bold text-gray-900 mb-8">{{ __('Send us a message') }}</h2>

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Phone Number') }}</label>
                            <input type="tel" name="phone" id="phone" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Subject') }}</label>
                            <input type="text" name="subject" id="subject" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all">
                        </div>

                        <div>
                            <label for="project_type" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Project Type (Optional)') }}</label>
                            <select name="project_type" id="project_type" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all">
                                <option value="">Select Project Type</option>
                                @foreach($projectTypes as $type)
                                    <option value="{{ $type->name }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="budget" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Budget (Optional)') }}</label>
                                <select name="budget" id="budget" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all">
                                    <option value="">Select Budget</option>
                                    @foreach($budgets as $budget)
                                        <option value="{{ $budget->name }}">{{ $budget->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="timeline" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Timeline (Optional)') }}</label>
                                <select name="timeline" id="timeline" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all">
                                    <option value="">Select Timeline</option>
                                    @foreach($timelines as $timeline)
                                        <option value="{{ $timeline->name }}">{{ $timeline->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Message') }} <span class="text-red-500">*</span></label>
                            <textarea id="message" name="message" rows="5" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary focus:border-transparent transition-all"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-brand-primary to-purple-600 text-white font-bold py-4 px-8 rounded-xl hover:from-brand-primary hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary shadow-xl transform hover:-translate-y-1 transition-all duration-200">
                            {{ __('Send Message') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-home::layouts.master>
