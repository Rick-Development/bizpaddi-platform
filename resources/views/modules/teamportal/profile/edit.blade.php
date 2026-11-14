@extends('teamportal::layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                <a href="{{ route('team.profile.index') }}" class="hover:text-emerald-400 transition-colors">My Profile</a>
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <span class="text-white">Edit</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Profile Settings</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">Update your personal information and password</p>
        </div>
        <a href="{{ route('team.profile.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-300 transition-colors">
            &larr; Back to Profile
        </a>
    </div>

    <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/10 p-6 sm:p-8">
        @if(session('success'))
        <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-xs sm:text-sm font-bold text-emerald-300">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('team.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-8 divide-y divide-white/5">
                <!-- Avatar -->
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 pt-2">
                    <div class="sm:col-span-6">
                        <h2 class="text-base font-bold text-white uppercase tracking-wider">Profile Picture</h2>
                    </div>
                    <div class="sm:col-span-6 flex flex-col sm:flex-row items-center gap-6">
                        <div class="h-24 w-24 flex-shrink-0 rounded-full bg-[#08110e] overflow-hidden ring-4 ring-emerald-500/30 border border-white/10 shadow-lg">
                            @if($user->getFirstMediaUrl('avatar'))
                                <img src="{{ $user->getFirstMediaUrl('avatar') }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-emerald-700 to-[#10231C] text-white text-3xl font-black">
                                     {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 w-full">
                            <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-xs text-gray-400 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-500 cursor-pointer">
                            @error('avatar') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            <p class="mt-2 text-[11px] text-gray-400">JPG, GIF or PNG. Max 2MB.</p>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 pt-8">
                    <div class="sm:col-span-6">
                        <h2 class="text-base font-bold text-white uppercase tracking-wider">Personal Information</h2>
                        <p class="mt-1 text-xs text-gray-400">Update your account details.</p>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="name" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="bg-[#08110e]/80 border border-white/10 text-white rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 block w-full text-xs sm:text-sm px-4 py-2.5 shadow-inner">
                        @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="email" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="bg-[#08110e]/80 border border-white/10 text-white rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 block w-full text-xs sm:text-sm px-4 py-2.5 shadow-inner">
                        @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="phone" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="bg-[#08110e]/80 border border-white/10 text-white rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 block w-full text-xs sm:text-sm px-4 py-2.5 shadow-inner">
                        @error('phone') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-6">
                        <label for="bio" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Bio <span class="text-gray-400 font-normal lowercase">(optional)</span></label>
                        <textarea name="bio" id="bio" rows="3" placeholder="Tell the team a little about yourself..." class="bg-[#08110e]/80 border border-white/10 text-white rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 block w-full text-xs sm:text-sm px-4 py-2.5 shadow-inner resize-none">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-gray-400">Max 1000 characters.</p>
                    </div>
                </div>

                <!-- Password Change -->
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 pt-8">
                    <div class="sm:col-span-6">
                        <h2 class="text-base font-bold text-white uppercase tracking-wider">Change Password</h2>
                        <p class="mt-1 text-xs text-gray-400">Leave blank if you don't want to change your password.</p>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="current_password" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="bg-[#08110e]/80 border border-white/10 text-white rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 block w-full text-xs sm:text-sm px-4 py-2.5 shadow-inner">
                        @error('current_password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="password" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">New Password</label>
                        <input type="password" name="password" id="password" class="bg-[#08110e]/80 border border-white/10 text-white rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 block w-full text-xs sm:text-sm px-4 py-2.5 shadow-inner">
                        @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-3">
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-300 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="bg-[#08110e]/80 border border-white/10 text-white rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 block w-full text-xs sm:text-sm px-4 py-2.5 shadow-inner">
                    </div>
                </div>
            </div>

            <div class="pt-8 flex justify-end">
                <button type="submit" class="inline-flex justify-center py-2.5 px-6 border border-emerald-500/30 text-xs sm:text-sm font-bold rounded-xl text-white bg-emerald-600 hover:bg-emerald-500 transition-all shadow-lg active:scale-95">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
