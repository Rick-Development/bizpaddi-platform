<x-home::layouts.master>
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-brand-secondary via-brand-primary to-purple-900 overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-extrabold text-white sm:text-6xl md:text-7xl">
                    {{ __('Our Blog') }}
                </h1>
                <p class="mt-6 max-w-3xl mx-auto text-xl text-gray-100">
                    {{ __('Insights, updates, and stories from the Rick Development team.') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Blog Posts Grid -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    <article class="group bg-white rounded-3xl shadow-xl overflow-hidden transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
                        <a href="{{ route('blog.show', $post->slug) }}" class="block">
                            <div class="relative h-56 overflow-hidden">
                                @if($post->featured_image)
                                    <img class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500" src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-green-400 to-cyan-600 flex items-center justify-center">
                                        <span class="text-6xl font-bold text-white opacity-50">{{ substr($post->title, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            </div>
                            
                            <div class="p-8">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="inline-block px-3 py-1 text-xs font-semibold text-brand-primary bg-red-50 rounded-full">
                                        {{ $post->category->name ?? 'Uncategorized' }}
                                    </span>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <time datetime="{{ $post->published_at }}">{{ $post->published_at->format('M d, Y') }}</time>
                                        <span class="mx-2">•</span>
                                        <span>{{ $post->reading_time }} min</span>
                                    </div>
                                </div>
                                
                                <h2 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-brand-primary transition-colors">
                                    {{ $post->clean_title }}
                                </h2>
                                
                                <p class="text-gray-600 mb-6">
                                    {{ Str::limit($post->clean_excerpt ?: strip_tags(html_entity_decode($post->content)), 150) }}
                                </p>
                                
                                <div class="flex items-center pt-6 border-t border-gray-100">
                                    @if(isset($post->author->profile_photo_url))
                                        <img class="w-10 h-10 rounded-full" src="{{ $post->author->profile_photo_url }}" alt="{{ $post->author->name }}">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-primary to-purple-600 flex items-center justify-center text-white font-bold">
                                            {{ substr($post->author->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <p class="text-sm font-semibold text-gray-900">{{ $post->author->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
            
            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-home::layouts.master>
