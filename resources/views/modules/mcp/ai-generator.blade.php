@extends('admin::layouts.app')

@section('title', 'AI Content Generator')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 -m-6 p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-brand-primary shadow-lg mb-4">
                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">AI Content Generator</h1>
            <p class="text-lg text-gray-600">Just tell me what you want, and I'll create it for you</p>
        </div>

        <!-- Main Form -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="p-6 sm:p-8">
                <form action="{{ route('admin.mcp.generate') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Prompt Input -->
                    <div>
                        <label for="prompt" class="block text-sm font-semibold text-gray-900 mb-2">
                            What would you like me to create?
                        </label>
                        <textarea 
                            id="prompt" 
                            name="prompt" 
                            rows="4" 
                            required
                            placeholder="Examples:&#10;• Write a blog post about Laravel best practices&#10;• Create a task for fixing the login bug&#10;• Generate a blog about modern PHP features"
                            class="block w-full rounded-xl border-2 border-gray-300 bg-white px-4 py-3 text-gray-900 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 text-base transition-all resize-none"
                        ></textarea>
                        <p class="mt-2 text-sm text-gray-500">Be specific! The more details you provide, the better the result.</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('admin.mcp.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                            </svg>
                            Back to Dashboard
                        </a>
                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-brand-primary text-white font-semibold shadow-lg hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all transform hover:scale-105 active:scale-95"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                            </svg>
                            Generate with AI
                        </button>
                    </div>
                </form>
            </div>

            <!-- Examples Section -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 px-6 py-6 sm:px-8 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">💡 What can I create for you?</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="bg-white rounded-lg p-3 border border-indigo-100 shadow-sm">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Blog Posts</p>
                                <p class="text-xs text-gray-500 mt-0.5">Full articles with title, content, and tags</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-purple-100 shadow-sm">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-purple-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Smart Tasks</p>
                                <p class="text-xs text-gray-500 mt-0.5">Auto-assigned to least busy developer</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Tips -->
        <div class="mt-6 bg-blue-50 rounded-lg p-4 border border-blue-200">
            <div class="flex">
                <svg class="h-5 w-5 text-blue-400 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-900">Pro Tip</h3>
                    <p class="mt-1 text-sm text-blue-700">The AI works best with clear, specific instructions. Instead of "write a blog", try "write a blog post about the top 5 Laravel security best practices for 2024".</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
