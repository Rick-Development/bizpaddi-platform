@extends('teamportal::layouts.app')

@section('title', 'Service Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">{{ $service->name }}</h1>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <a href="{{ route('team.services.index') }}" class="block rounded-md bg-gray-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-500">Back</a>
        </div>
    </div>

    <div class="mt-6 border-t border-gray-100">
        <dl class="divide-y divide-gray-100">
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Description</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $service->description }}</dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Price</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">${{ number_format($service->price, 2) }}</dd>
            </div>
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Status</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $service->is_active ? 'Active' : 'Inactive' }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
