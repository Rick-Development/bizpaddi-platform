@extends('admin::layouts.app')

@section('title', 'AI Settings')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">AI Center Settings</h1>
    <p class="mt-2 text-sm text-gray-700">Configure your AI providers and knowledge base.</p>
</div>

<div class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <form action="{{ route('admin.ai.settings.update') }}" method="POST">
            @csrf

            <!-- Active Provider -->
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 mb-6">
                <div class="sm:col-span-3">
                    <label for="active_provider" class="block text-sm font-medium text-gray-700">Active AI Provider</label>
                    <div class="mt-1">
                        <select id="active_provider" name="active_provider" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md">
                            <option value="openai" {{ ($settings['active_provider'] ?? '') == 'openai' ? 'selected' : '' }}>OpenAI (GPT-4/3.5)</option>
                            <option value="gemini" {{ ($settings['active_provider'] ?? '') == 'gemini' ? 'selected' : '' }}>Google Gemini (Free Tier Available)</option>
                            <option value="anthropic" {{ ($settings['active_provider'] ?? '') == 'anthropic' ? 'selected' : '' }}>Anthropic Claude</option>
                        </select>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Select the provider you want to use for the chat widget. 
                        <a href="{{ route('admin.ai.models.index') }}" class="text-brand-primary hover:underline font-medium">Manage Models</a>
                    </p>
                </div>
            </div>

            <hr class="my-6 border-gray-200">

            <!-- System Prompt (Knowledge Base) -->
            <div class="mb-6">
                <label for="system_prompt" class="block text-sm font-medium text-gray-700">
                    System Prompt / Knowledge Base
                </label>
                <div class="mt-1">
                    <p class="text-xs text-gray-500 mb-2">Teach the AI about Bizpaddi. Include key services, tone of voice, and contact policies.</p>
                    <textarea id="system_prompt" name="system_prompt" rows="10" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border border-gray-300 rounded-md">{{ $settings['system_prompt'] ?? "You are a helpful customer support assistant for Bizpaddi..." }}</textarea>
                </div>
            </div>

            <hr class="my-6 border-gray-200">

            <!-- API Keys -->
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Provider Credentials</h3>
            
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- OpenAI Key -->
                <div class="sm:col-span-4">
                    <label for="openai_key" class="block text-sm font-medium text-gray-700">OpenAI API Key</label>
                    <div class="mt-1">
                        <input type="password" name="openai_key" id="openai_key" value="{{ $settings['openai_key'] ?? '' }}" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md" placeholder="sk-...">
                    </div>
                </div>

                <!-- Gemini Key -->
                <div class="sm:col-span-4">
                    <label for="gemini_key" class="block text-sm font-medium text-gray-700">Google Gemini API Key</label>
                    <div class="mt-1">
                        <input type="password" name="gemini_key" id="gemini_key" value="{{ $settings['gemini_key'] ?? '' }}" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md" placeholder="AIza...">
                    </div>
                </div>

                <!-- Anthropic Key -->
                <div class="sm:col-span-4">
                    <label for="anthropic_key" class="block text-sm font-medium text-gray-700">Anthropic API Key</label>
                    <div class="mt-1">
                        <input type="password" name="anthropic_key" id="anthropic_key" value="{{ $settings['anthropic_key'] ?? '' }}" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md" placeholder="sk-ant-...">
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                    Save Configuration
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
