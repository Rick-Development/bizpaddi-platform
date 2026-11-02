@extends('admin::layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-white sm:truncate sm:text-3xl sm:tracking-tight">Edit Role: {{ ucfirst(str_replace('_', ' ', $role->name)) }}</h2>
            <p class="mt-2 text-sm text-gray-400">Modify the role name and update permission assignments.</p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0">
             <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure? This will remove permissions from all users with this role.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center rounded-md bg-[#10231C]/60 px-3 py-2 text-sm font-semibold text-red-600 shadow-sm ring-1 ring-inset ring-red-300 hover:bg-red-50 transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                    Delete Role
                </button>
            </form>
        </div>
    </div>

    <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="bg-[#10231C]/60 border border-white/10 ring-1 ring-gray-900/5 sm:rounded-xl">
        @csrf
        @method('PUT')
        
        <div class="p-8">
            <div class="max-w-2xl">
                <label for="name" class="block text-sm font-medium leading-6 text-white">Role Name</label>
                <div class="mt-2">
                    <input type="text" name="name" id="name" value="{{ $role->name }}" class="block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6" required>
                </div>
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
                                <input id="perm_{{ $permission->id }}" name="permissions[]" type="checkbox" value="{{ $permission->name }}" {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }} class="h-4 w-4 rounded border-white/10 text-brand-primary focus:ring-brand-primary cursor-pointer module-{{ $module }}">
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
            <button type="submit" class="rounded-md bg-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary transition-all">Update Role</button>
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
