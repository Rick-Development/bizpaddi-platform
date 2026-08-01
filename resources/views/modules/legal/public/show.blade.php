<x-home::layouts.master>
<div class="py-24 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
                {{ $page->title }}
            </h1>
            <p class="mt-4 text-sm text-gray-500">
                {{ __('Effective Date:') }} {{ $page->created_at->format('F d, Y') }}
                @if($page->effective_date)
                 | {{ __('Last Updated:') }} {{ \Carbon\Carbon::parse($page->effective_date)->format('F d, Y') }}
                @endif
            </p>
        </div>

        <div class="prose prose-lg prose-indigo mx-auto text-gray-500">
            {!! $page->content !!}
        </div>
    </div>
</div>
</x-home::layouts.master>
