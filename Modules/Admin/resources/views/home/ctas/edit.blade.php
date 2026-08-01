@extends('admin::layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Edit Home CTA') }}</h1>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form action="{{ route('admin.home.ctas.update', $cta->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">{{ __('Title') }}</label>
                        <input type="text" name="title" id="title" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('title', $cta->title) }}" required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>{{ old('description', $cta->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="button1_text" class="block text-sm font-medium text-gray-700">{{ __('Button 1 Text') }}</label>
                            <input type="text" name="button1_text" id="button1_text" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('button1_text', $cta->button1_text) }}">
                            @error('button1_text')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="button1_link" class="block text-sm font-medium text-gray-700">{{ __('Button 1 Link') }}</label>
                            <input type="text" name="button1_link" id="button1_link" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('button1_link', $cta->button1_link) }}">
                            @error('button1_link')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="button2_text" class="block text-sm font-medium text-gray-700">{{ __('Button 2 Text') }}</label>
                            <input type="text" name="button2_text" id="button2_text" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('button2_text', $cta->button2_text) }}">
                            @error('button2_text')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="button2_link" class="block text-sm font-medium text-gray-700">{{ __('Button 2 Link') }}</label>
                            <input type="text" name="button2_link" id="button2_link" class="mt-1 focus:ring-brand-primary focus:border-brand-primary block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" value="{{ old('button2_link', $cta->button2_link) }}">
                            @error('button2_link')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_active" name="is_active" type="checkbox" class="focus:ring-brand-primary h-4 w-4 text-brand-primary border-gray-300 rounded" value="1" {{ old('is_active', $cta->is_active) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-gray-700">{{ __('Active') }}</label>
                            <p class="text-gray-500">{{ __('Display this CTA on the home page.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('admin.home.ctas.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary mr-3">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-brand-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary">
                        {{ __('Update CTA') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
