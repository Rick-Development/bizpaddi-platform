<x-home::layouts.master>
    <div class="bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-3xl px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('investor.press-releases.index') }}" class="text-sm font-semibold leading-6 text-brand-primary hover:text-brand-secondary">
                    <span aria-hidden="true">←</span> Back to Press Releases
                </a>
            </div>
            
            <div class="text-base leading-7 text-gray-700">
                <div class="flex items-center gap-x-4 text-xs mb-4">
                    <time datetime="{{ $pressRelease->published_at->format('Y-m-d') }}" class="text-gray-500">{{ $pressRelease->published_at->format('F d, Y') }}</time>
                    <span class="rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600">{{ ucfirst($pressRelease->category) }}</span>
                </div>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">{{ $pressRelease->title }}</h1>
                
                <div class="mt-10 max-w-2xl prose prose-lg prose-indigo mx-auto text-gray-900">
                    {!! nl2br(e($pressRelease->content)) !!}
                </div>
            </div>
        </div>
    </div>
</x-home::layouts.master>
