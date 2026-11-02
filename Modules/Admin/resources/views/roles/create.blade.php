@extends('admin::layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-white sm:truncate sm:text-3xl sm:tracking-tight">Create New Role</h2>
            <p class="mt-2 text-sm text-gray-400">Define a new role and configure its specific permission set.</p>
        </div>
    </div>

    <form action="{{ route('admin.roles.store') }}" method="POST" class="bg-[#10231C]/60 border border-white/10 ring-1 ring-gray-900/5 sm:rounded-xl">
        @csrf
        <div class="p-8">
            <div class="max-w-2xl">
                <label for="name" class="block text-sm font-medium leading-6 text-white">Role Name</label>
                <div class="mt-2">
                    <input type="text" name="name" id="name" placeholder="e.g. Project Manager" class="block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 bg-[#0a1612] text-white border-white/10" required>
                </div>
                <p class="mt-2 text-sm text-gray-400">The internal identifier will be auto-generated (e.g. "Project Manager" &rarr; "project_manager").</p>
            </div>
        </div>

        <div class="border-t border-white/10 p-8">
            <h3 class="text-base font-semibold leading-6 text-white mb-6">Assign Permissions</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($permissions as $module => $modulePermissions)
                <div class="relative flex flex-col rounded-xl border border-white/10 bg-[#10231C]/60/50 p-4 shadow-sm hover:border-brand-primary/30 transition-colors">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-white/10">
                         <div class="flex items-center gap-2">
                             <div class="h-2 w-2 rounded-full bg-brand-primary/50"></div>
                             <h4 class="font-semibold text-white capitalize">{{ $module }}</h4>
                         </div>
                         <button type="button" class="text-xs font-medium text-brand-primary hover:text-brand-primary/80 transition-colors" onclick="toggleAll('{{ $module }}')">Select All</button>
                    </div>
                   
                    <div class="space-y-3 flex-1">
                        @foreach($modulePermissions as $permission)
                        <div class="relative flex items-start group">
                            <div class="flex h-5 items-center">
                                <input id="perm_{{ $permission->id }}" name="permissions[]" type="checkbox" value="{{ $permission->name }}" class="h-4 w-4 rounded border-white/10 text-brand-primary focus:ring-brand-primary cursor-pointer module-{{ $module }}">
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <label for="perm_{{ $permission->id }}" class="font-medium text-gray-400 group-hover:text-white transition-colors select-none cursor-pointer">
                                    {{ ucfirst(str_replace('_', ' ', explode('.', $permission->name)[1] ?? $permission->name)) }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end gap-x-4 px-8 py-4 bg-white/[0.03] rounded-b-xl border-t border-white/10">
            <a href="{{ route('admin.roles.index') }}" class="text-sm font-semibold leading-6 text-white hover:text-gray-300">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary transition-all">Create Role</button>
        </div>
    </form>
</div>

<script>
    function toggleAll(module) {
        const checkboxes = document.querySelectorAll(`.module-${module}`);
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => cb.checked = !allChecked);
    }
</script>
@endsection
