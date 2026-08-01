@extends('admin::layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Edit Home Feature') }}</h1>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.home.features.update', $feature->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">{{ __('Title') }}</label>
                        <input type="text" name="title" id="title" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('title', $feature->title) }}" required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>{{ old('description', $feature->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700">{{ __('Icon Class (FontAwesome)') }}</label>
                        <input type="text" name="icon" id="icon" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('icon', $feature->icon) }}" placeholder="fas fa-star">
                        @error('icon')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700">{{ __('Sort Order') }}</label>
                        <input type="number" name="sort_order" id="sort_order" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('sort_order', $feature->sort_order) }}">
                        @error('sort_order')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('admin.home.features.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary mr-3">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                        {{ __('Update Feature') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
