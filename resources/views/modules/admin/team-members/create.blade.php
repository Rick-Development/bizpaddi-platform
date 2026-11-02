@extends('admin::layouts.app')

@section('title', 'Add Team Member')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-x-4 border-b border-white/10 pb-5">
        <a href="{{ route('admin.team-members.index') }}" class="p-2 rounded-xl bg-white/5 border border-white/10 text-gray-400 hover:text-white hover:bg-white/10 transition">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Add Team Member</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">Create a new team member with department, role, and workspace permissions</p>
        </div>
    </div>

    <form action="{{ route('admin.team-members.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Details -->
                <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-white mb-4">Account Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Full Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('name') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Email (Login) *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('email') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Password *</label>
                            <input type="password" name="password" id="password" required minlength="8"
                                   class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            @error('password') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="phone" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                       class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                @error('phone') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="airtime_phone" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Airtime Phone</label>
                                <input type="text" name="airtime_phone" id="airtime_phone" value="{{ old('airtime_phone') }}" placeholder="For recharging"
                                       class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label for="data_phone" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Data Phone</label>
                                <input type="text" name="data_phone" id="data_phone" value="{{ old('data_phone') }}" placeholder="For data bundles"
                                       class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>

                        <div>
                            <label for="department" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Department *</label>
                            <select name="department" id="department" required
                                    class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="" class="bg-[#08110e] text-white">Select Department...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->name }}" class="bg-[#08110e] text-white" {{ old('department') == $dept->name ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="bio" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Bio</label>
                            <textarea name="bio" id="bio" rows="4" class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white placeholder-gray-500 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('bio') }}</textarea>
                            @error('bio') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="avatar" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Avatar / Photo</label>
                            <input type="file" name="avatar" id="avatar" accept="image/*" class="w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#0E5E4A] file:text-white hover:file:bg-emerald-600 transition">
                            @error('avatar') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-white mb-4">Role & Status</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="role" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Role *</label>
                            <select name="role" id="role" required
                                    class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="" class="bg-[#08110e] text-white">Select Role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" class="bg-[#08110e] text-white" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-1.5">Status *</label>
                            <select name="status" id="status" required
                                    class="w-full px-4 py-2.5 bg-[#0a1612] border border-white/15 rounded-xl text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="active" class="bg-[#08110e] text-white" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="suspended" class="bg-[#08110e] text-white" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="inactive" class="bg-[#08110e] text-white" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-xl shadow-lg shadow-emerald-900/30 text-xs font-bold text-white bg-[#0E5E4A] hover:bg-emerald-600 transition-all hover:-translate-y-0.5">
                        Add Team Member
                    </button>
                    <a href="{{ route('admin.team-members.index') }}" class="w-full flex justify-center py-2.5 px-4 border border-white/10 rounded-xl text-xs font-bold text-gray-300 bg-white/5 hover:bg-white/10 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
