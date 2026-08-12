@extends('teamportal::layouts.app')

@section('title', 'Lead Sources')

@section('content')
<div class="mb-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Lead Sources</h1>
            <p class="mt-2 text-sm text-gray-600">Manage sources where your leads come from</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('team.bdm.lead-sources.create') }}" class="inline-flex items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-[#FB4C4B] to-[#D93837] hover:from-[#D93837] hover:to-[#FB4C4B] shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Add Lead Source
            </a>
        </div>
    </div>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Color</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($sources as $source)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $source->name }}</div>
                        <div class="text-xs text-gray-500">{{ $source->slug }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600 line-clamp-1">{{ $source->description ?: '—' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="h-4 w-4 rounded-full mr-2" style="background-color: {{ $source->color }}"></span>
                            <span class="text-sm text-gray-600">{{ $source->color }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($source->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('team.bdm.lead-sources.edit', $source) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('team.bdm.lead-sources.destroy', $source) }}" method="POST" onsubmit="return confirm('Delete this lead source?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                        No lead sources defined yet.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $sources->links() }}
</div>
@endsection
