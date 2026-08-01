<x-home::layouts.master>
    <div class="bg-brand-secondary py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-5xl">Press Releases</h1>
            <p class="mt-4 text-lg leading-8 text-gray-300">Latest news and announcements from Rick Development.</p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-6 lg:px-8 py-16">
        @if($pressReleases->count() > 0)
            <div class="space-y-8">
                @foreach($pressReleases as $release)
                    <article class="flex flex-col items-start justify-between bg-white p-6 rounded-2xl shadow-sm ring-1 ring-gray-900/5 hover:shadow-md transition-all duration-300 sm:flex-row sm:items-center">
                        <div class="max-w-xl">
                            <div class="flex items-center gap-x-4 text-xs">
                                <time datetime="{{ $release->published_at->format('Y-m-d') }}" class="text-gray-500">{{ $release->published_at->format('M d, Y') }}</time>
                                <span class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">{{ ucfirst($release->category) }}</span>
                            </div>
                            <div class="group relative">
                                <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-brand-primary">
                                    <a href="{{ route('investor.press-releases.show', $release->slug) }}">
                                        <span class="absolute inset-0"></span>
                                        {{ $release->title }}
                                    </a>
                                </h3>
                                <p class="mt-5 line-clamp-2 text-sm leading-6 text-gray-600">{{ $release->excerpt }}</p>
                            </div>
                        </div>
                        <div class="mt-4 sm:ml-6 sm:mt-0 sm:flex-shrink-0">
                            <a href="{{ route('investor.press-releases.show', $release->slug) }}" class="text-sm font-semibold leading-6 text-brand-primary hover:text-brand-secondary">
                                Read more <span aria-hidden="true">→</span>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
            
            <div class="mt-10">
                {{ $pressReleases->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <p class="text-gray-500">No press releases found.</p>
            </div>
        @endif
    </div>
</x-home::layouts.master>
