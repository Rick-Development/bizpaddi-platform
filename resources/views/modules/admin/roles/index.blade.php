@extends('admin::layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold leading-7 text-white sm:truncate sm:text-3xl sm:tracking-tight">Roles</h1>
            <p class="mt-2 text-sm text-gray-400">Define roles and assign granular permissions to control access.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('admin.roles.create') }}" class="block rounded-md bg-brand-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary transition-colors">
                <span class="flex items-center gap-2">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    New Role
                </span>
            </a>
        </div>
    </div>

    <!-- Mobile View (Cards) -->
    <div class="mt-8 sm:hidden space-y-4">
        @forelse($roles as $role)
        <div class="bg-[#10231C]/60 px-4 py-5 shadow sm:rounded-lg sm:px-6 border border-white/10 rounded-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold leading-6 text-white">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</h3>
                <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                    {{ $role->permissions->count() }} Perms
                </span>
            </div>
            <div class="mt-4 flex items-center justify-end border-t border-white/10 pt-3">
                 <a href="{{ route('admin.roles.edit', $role) }}" class="text-sm font-medium text-brand-primary hover:text-brand-primary/80">Manage Permissions &rarr;</a>
            </div>
        </div>
        @empty
        <div class="text-center py-6 bg-[#10231C]/60 rounded-xl border border-dashed border-white/10">
             <p class="text-sm text-gray-400">No roles found.</p>
        </div>
        @endforelse
    </div>

    <!-- Desktop View (Table) -->
    <div class="hidden sm:block mt-8 flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg bg-[#10231C]/60">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-[#10231C]/60/[0.04]">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Role Name</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Permissions</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Users</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 bg-transparent">
                            @forelse($roles as $role)
                            <tr class="hover:bg-white/[0.03] transition-colors">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                                    <div class="flex items-center">
                                        <div class="h-9 w-9 flex-shrink-0 flex items-center justify-center rounded-full bg-brand-primary/10 text-brand-primary font-bold">
                                             {{ strtoupper(substr($role->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4 font-medium">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                        {{ $role->permissions->count() }} assigned
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                    {{ $role->users()->count() }} users
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="text-brand-primary hover:text-brand-primary/80 font-semibold">Configure<span class="sr-only">, {{ $role->name }}</span></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-400">
                                    <h3 class="mt-2 text-sm font-semibold text-white">No roles found</h3>
                                    <p class="mt-1 text-sm text-gray-400">Create a role to get started.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-6">
        {{ $roles->links() }}
    </div>
</div>
@endsection
