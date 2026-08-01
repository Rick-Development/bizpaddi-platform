@extends('admin::layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Create Home Stat') }}</h1>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.home.stats.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="value" class="block text-sm font-medium text-gray-700">{{ __('Value') }}</label>
                        <input type="text" name="value" id="value" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('value') }}" required placeholder="e.g. 500+">
                        @error('value')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="label" class="block text-sm font-medium text-gray-700">{{ __('Label') }}</label>
                        <input type="text" name="label" id="label" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('label') }}" required placeholder="e.g. Projects Completed">
                        @error('label')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700">{{ __('Sort Order') }}</label>
                        <input type="number" name="sort_order" id="sort_order" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('sort_order', 0) }}">
                        @error('sort_order')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('admin.home.stats.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary mr-3">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                        {{ __('Create Stat') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
