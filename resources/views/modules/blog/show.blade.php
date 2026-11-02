<x-home::layouts.master :seoModel="$post">
    @php
        // Pass the post model to the layout for SEO rendering
    @endphp

    <!-- Post Header -->
    <div class="relative bg-gray-900 py-24 sm:py-32 overflow-hidden">
        @if($post->featured_image)
            <div class="absolute inset-0">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover opacity-30 blur-sm">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40"></div>
            </div>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-brand-primary to-purple-900 opacity-90"></div>
        @endif

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                @if($post->category)
                    <div class="mb-8">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-brand-primary text-white">
                            {{ $post->category->name }}
                        </span>
                    </div>
                @endif
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl">{{ $post->clean_title }}</h1>
                <div class="mt-8 flex items-center justify-center gap-x-4 text-sm text-gray-300">
                    <div class="flex items-center gap-x-2">
                        @if(isset($post->author->profile_photo_url))
                            <img src="{{ $post->author->profile_photo_url }}" alt="{{ $post->author->name }}" class="h-8 w-8 rounded-full bg-gray-800">
                        @else
                            <div class="h-8 w-8 rounded-full bg-brand-primary flex items-center justify-center text-white font-bold text-xs">
                                {{ substr($post->author->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="font-semibold text-white">{{ $post->author->name }}</span>
                    </div>
                    <span>&middot;</span>
                    <time datetime="{{ $post->published_at }}">{{ $post->published_at->format('M d, Y') }}</time>
                    <span>&middot;</span>
                    <span>{{ $post->reading_time }} min read</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Post Content -->
    <div class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            @if($post->excerpt)
                <div class="text-xl leading-8 text-gray-900 mb-10 font-medium italic border-l-4 border-brand-primary pl-6">
                    {{ $post->clean_excerpt }}
                </div>
            @endif

            @if($post->featured_image)
                <figure class="my-10">
                    <img class="aspect-video rounded-xl bg-gray-50 object-cover shadow-xl" src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}">
                </figure>
            @endif

            <div class="prose prose-lg prose-red mx-auto max-w-none">
                {!! $post->content !!}
            </div>

            <!-- CTA Block -->
            <div class="mt-16 bg-gradient-to-br from-brand-primary to-brand-secondary rounded-3xl p-10 sm:p-12 text-center text-white shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-32 h-32 bg-purple-500 opacity-20 rounded-full blur-2xl"></div>
                <h3 class="text-3xl font-extrabold mb-4 relative z-10">Ready to transform your ideas into reality?</h3>
                <p class="text-lg text-gray-200 mb-8 max-w-2xl mx-auto relative z-10">
                    Join the innovative companies that trust Bizpaddi to build their future. Let's discuss your next project.
                </p>
                <a href="/start-project" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-brand-primary bg-white rounded-full hover:bg-gray-50 transform hover:-translate-y-1 transition-all duration-300 shadow-xl relative z-10">
                    Start Your Project
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>

            <!-- Share & Tags -->
            <div class="mt-16 pt-8 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                    @if($post->tags->count() > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @elseif($post->meta_keywords)
                         <div class="flex flex-wrap gap-2">
                            @foreach(explode(',', $post->meta_keywords) as $keyword)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    #{{ trim($keyword) }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="flex items-center gap-4">
                        <span class="text-gray-500 font-medium">Share:</span>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->clean_title) }}&url={{ urlencode(request()->url()) }}" target="_blank" class="text-gray-400 hover:text-brand-primary transition-colors">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->clean_title) }}" target="_blank" class="text-gray-400 hover:text-blue-700 transition-colors">
                            <span class="sr-only">LinkedIn</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" /></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <a href="{{ route('blog.index') }}" class="inline-flex items-center text-brand-primary font-medium hover:text-red-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to All Articles
                </a>
            </div>
        </div>
    </div>
</x-home::layouts.master>
