@extends('admin::layouts.app')

@section('title', 'SMS Templates')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">SMS Templates</h1>
        <p class="mt-1 text-sm text-gray-500">Manage templates for your SMS campaigns.</p>
    </div>
    <a href="{{ route('admin.sms-templates.create') }}" class="inline-flex items-center rounded-lg bg-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary transition-all">
        <i class="fa-solid fa-plus mr-2"></i>
        Create Template
    </a>
</div>

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Created At</th>
                <th scope="col" class="relative px-6 py-4">
                    <span class="sr-only">Actions</span>
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            @forelse($templates as $template)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="whitespace-nowrap px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $template->name }}</div>
                </td>
                <td class="whitespace-nowrap px-6 py-4">
                    @if($template->is_active)
                        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Active</span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">Inactive</span>
                    @endif
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                    {{ $template->created_at->format('M d, Y') }}
                </td>
                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                    <div class="flex justify-end gap-x-3">
                        <a href="{{ route('admin.sms-templates.edit', $template) }}" class="text-brand-primary hover:text-brand-primary/80 transition-colors">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.sms-templates.destroy', $template) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this template?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fa-solid fa-message mb-3 text-4xl text-gray-300"></i>
                        <p>No SMS templates found.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $templates->links() }}
</div>
@endsection
