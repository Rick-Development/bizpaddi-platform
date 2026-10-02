@extends('admin::layouts.app')

@section('title', 'Edit Team Member')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-x-4">
        <a href="{{ route('admin.team-members.index') }}" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Team Member: {{ $teamMember->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Update team member details and permissions</p>
        </div>
    </div>
</div>

<form action="{{ route('admin.team-members.update', $teamMember) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Basic Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $teamMember->name) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (Login) *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $teamMember->email) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" id="password" minlength="8" placeholder="Leave empty to keep current"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        <p class="mt-1 text-xs text-gray-500">Only fill if you want to change the password.</p>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $teamMember->phone) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="airtime_phone" class="block text-sm font-medium text-gray-700 mb-1">Airtime Phone</label>
                            <input type="text" name="airtime_phone" id="airtime_phone" value="{{ old('airtime_phone', $teamMember->airtime_phone) }}" placeholder="For recharging"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>
                        <div>
                            <label for="data_phone" class="block text-sm font-medium text-gray-700 mb-1">Data Phone</label>
                            <input type="text" name="data_phone" id="data_phone" value="{{ old('data_phone', $teamMember->data_phone) }}" placeholder="For data bundles"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                        <select name="department" id="department" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            <option value="">Select Department...</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->name }}" {{ old('department', $teamMember->department) == $dept->name ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                        <textarea name="bio" id="bio" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">{{ old('bio', $teamMember->bio) }}</textarea>
                        @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Avatar / Photo</label>
                        @if($teamMember->avatar)
                            <div class="mt-2 mb-4">
                                <img src="{{ asset('storage/' . $teamMember->avatar) }}" alt="Current Avatar" class="h-20 w-20 object-cover rounded-full shadow-sm">
                            </div>
                        @endif
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="w-full text-sm text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-primary file:text-white hover:file:bg-red-700">
                        @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Role & Status</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                        <select name="role" id="role" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $teamMember->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" id="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-primary focus:border-transparent">
                            <option value="active" {{ old('status', $teamMember->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ old('status', $teamMember->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="inactive" {{ old('status', $teamMember->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 rounded-xl shadow-lg shadow-brand-primary/30 text-sm font-medium text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary transition-all hover:-translate-y-0.5">
                    Update Team Member
                </button>
                <a href="{{ route('admin.team-members.index') }}" class="w-full flex justify-center py-2.5 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-colors">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
