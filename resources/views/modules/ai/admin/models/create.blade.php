@extends('admin::layouts.app')

@section('title', 'Add AI Model')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-xl font-semibold text-gray-900">Add New AI Model</h1>
    </div>

    <form action="{{ route('admin.ai.models.store') }}" method="POST" class="space-y-6 max-w-2xl">
        @csrf
        
        <div class="bg-white shadow sm:rounded-lg p-6">
            <div class="grid grid-cols-1 gap-y-6">
                <!-- Provider -->
                <div>
                    <label for="provider" class="block text-sm font-medium text-gray-700">Provider</label>
                    <select name="provider" id="provider" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                        <option value="openai">OpenAI</option>
                        <option value="anthropic">Anthropic</option>
                        <option value="gemini">Gemini</option>
                    </select>
                </div>

                <!-- Name (API ID) -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">API Model ID</label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" placeholder="e.g. gpt-4o, claude-3-5-sonnet-20240620" required
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm font-mono">
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        The exact model string required by the API. <br>
                        References: 
                        <a href="https://platform.openai.com/docs/models" target="_blank" class="text-brand-primary hover:underline">OpenAI Models</a> | 
                        <a href="https://docs.anthropic.com/en/docs/models-overview" target="_blank" class="text-brand-primary hover:underline">Anthropic Models</a> | 
                        <a href="https://ai.google.dev/gemini-api/docs/models/gemini" target="_blank" class="text-brand-primary hover:underline">Gemini Models</a>
                    </p>
                </div>

                <!-- Label -->
                <div>
                    <label for="label" class="block text-sm font-medium text-gray-700">Label (Optional)</label>
                    <div class="mt-1">
                        <input type="text" name="label" id="label" placeholder="e.g. GPT-4o"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary sm:text-sm">
                    </div>
                </div>

                <!-- Is Active -->
                <div class="relative flex items-start">
                    <div class="flex h-5 items-center">
                        <input id="is_active" name="is_active" type="checkbox" value="1" checked class="h-4 w-4 rounded border-gray-300 text-brand-primary focus:ring-brand-primary">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">Active</label>
                        <p class="text-gray-500">Enable this model for selection.</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('admin.ai.models.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">Create Model</button>
            </div>
        </div>
    </form>
</div>
@endsection
