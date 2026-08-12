@extends('admin::layouts.app')

@section('title', 'Lead Form Settings')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-semibold text-gray-900">Lead Form Settings</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Project Types -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Project Types</h2>
            <button onclick="document.getElementById('add-type-modal').classList.remove('hidden')" class="text-sm bg-brand-primary text-white px-3 py-1 rounded hover:bg-brand-primary/90">Add</button>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Name</th>
                        <th class="text-center text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Order</th>
                        <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($projectTypes as $type)
                    <tr>
                        <td class="py-2 text-sm text-gray-900">{{ $type->name }} <br><span class="text-xs {{ $type->is_active ? 'text-green-500' : 'text-red-500' }}">{{ $type->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="py-2 text-sm text-gray-500 text-center">{{ $type->sort_order }}</td>
                        <td class="py-2 text-right text-sm font-medium">
                            <button onclick="document.getElementById('edit-type-modal-{{ $type->id }}').classList.remove('hidden')" class="text-brand-primary hover:text-brand-primary/80 text-xs mr-2">Edit</button>
                            <form action="{{ route('admin.lead-settings.types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this project type?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <!-- Edit Modal -->
                    <div id="edit-type-modal-{{ $type->id }}" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center z-50 text-left">
                        <div class="bg-white rounded-lg p-6 max-w-md w-full">
                            <h3 class="text-lg font-medium mb-4">Edit Project Type</h3>
                            <form action="{{ route('admin.lead-settings.types.update', $type) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" value="{{ $type->name }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                                    <input type="number" name="sort_order" value="{{ $type->sort_order }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary">
                                </div>
                                <div class="mb-4">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_active" value="1" {{ $type->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-brand-primary shadow-sm focus:border-brand-primary focus:ring focus:ring-brand-primary focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">Active</span>
                                    </label>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="document.getElementById('edit-type-modal-{{ $type->id }}').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary/90">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Budgets -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Budget Ranges</h2>
            <button onclick="document.getElementById('add-budget-modal').classList.remove('hidden')" class="text-sm bg-brand-primary text-white px-3 py-1 rounded hover:bg-brand-primary/90">Add</button>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Name</th>
                        <th class="text-center text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Order</th>
                        <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($budgets as $budget)
                    <tr>
                        <td class="py-2 text-sm text-gray-900">{{ $budget->name }} <br><span class="text-xs {{ $budget->is_active ? 'text-green-500' : 'text-red-500' }}">{{ $budget->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="py-2 text-sm text-gray-500 text-center">{{ $budget->sort_order }}</td>
                        <td class="py-2 text-right text-sm font-medium">
                            <button onclick="document.getElementById('edit-budget-modal-{{ $budget->id }}').classList.remove('hidden')" class="text-brand-primary hover:text-brand-primary/80 text-xs mr-2">Edit</button>
                            <form action="{{ route('admin.lead-settings.budgets.destroy', $budget) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this budget range?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <!-- Edit Modal -->
                    <div id="edit-budget-modal-{{ $budget->id }}" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center z-50 text-left">
                        <div class="bg-white rounded-lg p-6 max-w-md w-full">
                            <h3 class="text-lg font-medium mb-4">Edit Budget Range</h3>
                            <form action="{{ route('admin.lead-settings.budgets.update', $budget) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" value="{{ $budget->name }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                                    <input type="number" name="sort_order" value="{{ $budget->sort_order }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary">
                                </div>
                                <div class="mb-4">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_active" value="1" {{ $budget->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-brand-primary shadow-sm focus:border-brand-primary focus:ring focus:ring-brand-primary focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">Active</span>
                                    </label>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="document.getElementById('edit-budget-modal-{{ $budget->id }}').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary/90">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Timelines -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Timelines</h2>
            <button onclick="document.getElementById('add-timeline-modal').classList.remove('hidden')" class="text-sm bg-brand-primary text-white px-3 py-1 rounded hover:bg-brand-primary/90">Add</button>
        </div>
        <div class="p-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Name</th>
                        <th class="text-center text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Order</th>
                        <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider pb-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($timelines as $timeline)
                    <tr>
                        <td class="py-2 text-sm text-gray-900">{{ $timeline->name }} <br><span class="text-xs {{ $timeline->is_active ? 'text-green-500' : 'text-red-500' }}">{{ $timeline->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td class="py-2 text-sm text-gray-500 text-center">{{ $timeline->sort_order }}</td>
                        <td class="py-2 text-right text-sm font-medium">
                            <button onclick="document.getElementById('edit-timeline-modal-{{ $timeline->id }}').classList.remove('hidden')" class="text-brand-primary hover:text-brand-primary/80 text-xs mr-2">Edit</button>
                            <form action="{{ route('admin.lead-settings.timelines.destroy', $timeline) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this timeline?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <!-- Edit Modal -->
                    <div id="edit-timeline-modal-{{ $timeline->id }}" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center z-50 text-left">
                        <div class="bg-white rounded-lg p-6 max-w-md w-full">
                            <h3 class="text-lg font-medium mb-4">Edit Timeline</h3>
                            <form action="{{ route('admin.lead-settings.timelines.update', $timeline) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <input type="text" name="name" value="{{ $timeline->name }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                                    <input type="number" name="sort_order" value="{{ $timeline->sort_order }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary">
                                </div>
                                <div class="mb-4">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_active" value="1" {{ $timeline->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-brand-primary shadow-sm focus:border-brand-primary focus:ring focus:ring-brand-primary focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-600">Active</span>
                                    </label>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="document.getElementById('edit-timeline-modal-{{ $timeline->id }}').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                                    <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary/90">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modals for adding items -->
<div id="add-type-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium mb-4">Add Project Type</h3>
        <form action="{{ route('admin.lead-settings.types.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                <input type="number" name="sort_order" value="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary">
            </div>
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-brand-primary shadow-sm focus:border-brand-primary focus:ring focus:ring-brand-primary focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-600">Active</span>
                </label>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('add-type-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary/90">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="add-budget-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium mb-4">Add Budget Range</h3>
        <form action="{{ route('admin.lead-settings.budgets.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                <input type="number" name="sort_order" value="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary">
            </div>
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-brand-primary shadow-sm focus:border-brand-primary focus:ring focus:ring-brand-primary focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-600">Active</span>
                </label>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('add-budget-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary/90">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="add-timeline-modal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium mb-4">Add Timeline</h3>
        <form action="{{ route('admin.lead-settings.timelines.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                <input type="number" name="sort_order" value="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-primary focus:ring-brand-primary">
            </div>
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-brand-primary shadow-sm focus:border-brand-primary focus:ring focus:ring-brand-primary focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-600">Active</span>
                </label>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('add-timeline-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-brand-primary text-white rounded-md hover:bg-brand-primary/90">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection
