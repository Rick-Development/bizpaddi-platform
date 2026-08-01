@extends('admin::layouts.app')

@section('title', 'AI Models')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">AI Models</h1>
            <p class="mt-2 text-sm text-gray-700">Manage AI models for different providers (OpenAI, Anthropic, Gemini).</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.ai.models.create') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-brand-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 sm:w-auto">
                Add Model
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="mt-4 bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Provider</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Name (API ID)</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Label</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($models as $model)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 capitalize">{{ $model->provider }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-mono">{{ $model->name }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $model->label }}</td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $model->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $model->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <a href="{{ route('admin.ai.models.edit', $model) }}" class="text-brand-primary hover:text-red-900 mr-4">Edit</a>
                                        <form action="{{ route('admin.ai.models.destroy', $model) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">No models found. Add one to get started.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
