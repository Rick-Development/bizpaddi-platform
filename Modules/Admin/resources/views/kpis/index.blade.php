@extends('admin::layouts.app')

@section('title', 'KPI Management')

@section('content')
<div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-2xl font-semibold leading-6 text-gray-900">KPI Management</h1>
        <p class="mt-2 text-sm text-gray-700">A list of all Performance Indicators used to track team progress.</p>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <a href="{{ route('admin.kpis.create') }}" class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">
            Add KPI
        </a>
    </div>
</div>

<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Key</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Target</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Unit</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($kpis as $kpi)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $kpi->name }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $kpi->key }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 font-bold text-brand-primary">{{ $kpi->target }}</td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 uppercase">{{ $kpi->unit }}</td>
                            <td class="px-3 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $kpi->description }}</td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <div class="flex justify-end gap-x-3">
                                    <a href="{{ route('admin.kpis.edit', $kpi) }}" class="text-brand-primary hover:text-brand-primary/80">Edit</a>
                                    <form action="{{ route('admin.kpis.destroy', $kpi) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this KPI?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-sm text-gray-500">No KPIs found. Start by adding one!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($kpis->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $kpis->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
