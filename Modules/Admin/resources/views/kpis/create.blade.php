@extends('admin::layouts.app')

@section('title', 'Create KPI')

@section('content')
<div class="md:flex md:items-center md:justify-between mb-8">
    <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-white sm:truncate sm:text-3xl sm:tracking-tight">Create KPI Indicator</h2>
    </div>
</div>

<div class="bg-[#10231C]/60 border border-white/10 ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
    <form action="{{ route('admin.kpis.store') }}" method="POST" class="px-4 py-6 sm:p-8">
        @csrf
        <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="name" class="block text-sm font-medium leading-6 text-white">KPI Name</label>
                <div class="mt-2">
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 bg-[#0a1612] text-white border-white/10" placeholder="e.g. Sales Growth">
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }} </p> @enderror
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="key" class="block text-sm font-medium leading-6 text-white">Unique Key (Identifier)</label>
                <div class="mt-2">
                    <input type="text" name="key" id="key" value="{{ old('key') }}" class="block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 bg-[#0a1612] text-white border-white/10" placeholder="e.g. sales_growth">
                    @error('key') <p class="mt-2 text-sm text-red-600">{{ $message }} </p> @enderror
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="target" class="block text-sm font-medium leading-6 text-white">Target Value</label>
                <div class="mt-2">
                    <input type="number" step="0.01" name="target" id="target" value="{{ old('target') }}" class="block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 bg-[#0a1612] text-white border-white/10">
                    @error('target') <p class="mt-2 text-sm text-red-600">{{ $message }} </p> @enderror
                </div>
            </div>

            <div class="sm:col-span-3">
                <label for="unit" class="block text-sm font-medium leading-6 text-white">Unit</label>
                <div class="mt-2">
                    <input type="text" name="unit" id="unit" value="{{ old('unit', '%') }}" class="block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 bg-[#0a1612] text-white border-white/10" placeholder="e.g. %, USD, count">
                    @error('unit') <p class="mt-2 text-sm text-red-600">{{ $message }} </p> @enderror
                </div>
            </div>

            <div class="col-span-full">
                <label for="description" class="block text-sm font-medium leading-6 text-white">Description</label>
                <div class="mt-2">
                    <textarea id="description" name="description" rows="3" class="block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-brand-primary sm:text-sm sm:leading-6 bg-[#0a1612] text-white border-white/10">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }} </p> @enderror
                </div>
                <p class="mt-3 text-sm leading-6 text-gray-400">Briefly describe what this KPI measures.</p>
            </div>
        </div>

        <div class="mt-8 flex items-center gap-x-6">
            <button type="submit" class="rounded-md bg-brand-primary px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brand-primary/80 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-primary">Save KPI</button>
            <a href="{{ route('admin.kpis.index') }}" class="text-sm font-semibold leading-6 text-white">Cancel</a>
        </div>
    </form>
</div>
@endsection
