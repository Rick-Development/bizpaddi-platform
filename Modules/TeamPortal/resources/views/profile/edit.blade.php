@extends('teamportal::layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Profile Settings</h1>
            <p class="mt-2 text-sm text-gray-700">Update your personal information and password</p>
        </div>
    </div>

    <div class="max-w-4xl">
        <div class="bg-white rounded-lg shadow border border-gray-200 p-8">
            @if(session('success'))
            <div class="mb-6 rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('team.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-8 divide-y divide-gray-100">
                    <!-- Avatar -->
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 pt-4">
                        <div class="sm:col-span-6">
                            <h2 class="text-lg font-medium text-gray-900">Profile Picture</h2>
                        </div>
                        <div class="sm:col-span-6 flex items-center gap-6">
                            <div class="h-24 w-24 flex-shrink-0 rounded-full bg-gray-100 overflow-hidden ring-4 ring-white shadow-sm">
                                @if($user->getFirstMediaUrl('avatar'))
                                    <img src="{{ $user->getFirstMediaUrl('avatar') }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-brand-primary text-white text-3xl font-bold">
                                         {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <input type="file" name="avatar" id="avatar" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-brand-primary file:text-white hover:file:bg-brand-primary/90 cursor-pointer shadow-sm">
                                @error('avatar') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                <p class="mt-2 text-xs text-gray-500">JPG, GIF or PNG. Max 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 pt-8">
                        <div class="sm:col-span-6">
                            <h2 class="text-lg font-medium text-gray-900">Personal Information</h2>
                            <p class="mt-1 text-sm text-gray-500">Update your account details.</p>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md px-4 py-2">
                            </div>
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md px-4 py-2">
                            </div>
                            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <div class="mt-1">
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md px-4 py-2">
                            </div>
                            @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Password Change -->
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 pt-8">
                        <div class="sm:col-span-6">
                            <h2 class="text-lg font-medium text-gray-900">Change Password</h2>
                            <p class="mt-1 text-sm text-gray-500">Leave blank if you don't want to change your password.</p>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                            <div class="mt-1">
                                <input type="password" name="current_password" id="current_password" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md px-4 py-2">
                            </div>
                            @error('current_password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                            <div class="mt-1">
                                <input type="password" name="password" id="password" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md px-4 py-2">
                            </div>
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-3">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                            <div class="mt-1">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border-gray-300 rounded-md px-4 py-2">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 flex justify-end">
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-brand-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary shadow-brand-primary/30 transition-all hover:-translate-y-0.5">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
