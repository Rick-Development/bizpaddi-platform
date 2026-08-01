@extends('admin::layouts.app')

@section('title', 'SEO Manager')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">SEO Manager</h1>
    <p class="mt-2 text-sm text-gray-600">Manage global search engine optimization settings for your website.</p>
</div>

<div class="max-w-4xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.seo.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Global Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Global Title Suffix / Default Title</label>
                <div class="mt-1">
                    <input type="text" name="title" id="title" value="{{ old('title', $seo->title) }}" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-xl px-4 py-3" placeholder="e.g. My Website | Best Service">
                </div>
                <p class="mt-2 text-sm text-gray-500">This will be used as the default title or appended to page titles.</p>
            </div>

            <!-- Global Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Default Meta Description</label>
                <div class="mt-1">
                    <textarea name="description" id="description" rows="3" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-xl px-4 py-3">{{ old('description', $seo->description) }}</textarea>
                </div>
                <p class="mt-2 text-sm text-gray-500">Used when a specific page doesn't have a meta description.</p>
            </div>

            <!-- Global Keywords -->
            <div>
                <label for="keywords" class="block text-sm font-medium text-gray-700">Default Keywords</label>
                <div class="mt-1">
                    <input type="text" name="keywords" id="keywords" value="{{ old('keywords', $seo->keywords) }}" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-xl px-4 py-3" placeholder="keyword1, keyword2, keyword3">
                </div>
                <p class="mt-2 text-sm text-gray-500">Comma separated keywords.</p>
            </div>

            <!-- Default OG Image -->
            <div>
                <label for="og_image" class="block text-sm font-medium text-gray-700">Default Social Share Image (URL)</label>
                <div class="mt-1">
                    <input type="url" name="og_image" id="og_image" value="{{ old('og_image', $seo->og_image) }}" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-xl px-4 py-3" placeholder="https://example.com/logo.png">
                </div>
            </div>

            <div class="pt-5 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
