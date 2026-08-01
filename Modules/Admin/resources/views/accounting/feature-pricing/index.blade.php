@extends('admin::layouts.app')

@section('title', 'Feature Pricing')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Feature Pricing</h1>
            <p class="mt-1 text-sm text-gray-600">Manage software feature prices (Auth Module, Bill Payment, VTU, etc.)</p>
        </div>
        <a href="{{ route('admin.accounting.feature-pricing.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors shadow-sm">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Feature
        </a>
    </div>
</div>

@if($features->isEmpty())
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <h3 class="mt-4 text-lg font-semibold text-gray-900">No features yet</h3>
    <p class="mt-2 text-sm text-gray-600">Add your first feature pricing (e.g., Auth Module - 200k, Bill Payment - 300k)</p>
    <div class="mt-6">
        <a href="{{ route('admin.accounting.feature-pricing.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Feature
        </a>
    </div>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($features as $feature)
    <div class="group relative bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col overflow-visible">
        <!-- Floating Price -->
        <div class="absolute -top-3 -right-3 z-10">
            <div class="bg-indigo-600 text-white text-md font-bold px-4 py-2 rounded-2xl shadow-lg transform group-hover:scale-110 transition-transform duration-300 rotate-2 group-hover:rotate-0">
                {{ $feature->currency }} {{ number_format($feature->price, 2) }}
            </div>
        </div>

        <div class="p-6 flex flex-col flex-1">
             <!-- Header -->
            <div class="mb-4 pr-12">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-mono font-medium mb-3 bg-gray-100 text-gray-500">
                     {{ $feature->feature_code }}
                </span>
                <h3 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 leading-tight" title="{{ $feature->feature_name }}">{{ $feature->feature_name }}</h3>
            </div>

             <!-- Description -->
            <div class="flex-1 mb-6">
                <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed">
                    {{ $feature->description ?? 'No description provided.' }}
                </p>
            </div>

            <!-- Billing Info -->
            <div class="mb-4">
                 <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-indigo-50 border border-indigo-100 w-full">
                     <svg class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                     </svg>
                     <span class="text-xs font-semibold text-indigo-700 uppercase tracking-wide">
                        {{ str_replace('_', ' ', $feature->billing_type) }} Billing
                     </span>
                 </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-auto">
                 <div class="flex items-center gap-2">
                    @if($feature->is_active)
                        <div class="flex items-center gap-1.5 px-2 py-1 bg-green-50 rounded-lg border border-green-100">
                             <div class="h-2 w-2 rounded-full bg-green-500 shadow-sm"></div>
                            <span class="text-xs font-semibold text-green-700">Active</span>
                        </div>
                    @else
                        <div class="flex items-center gap-1.5 px-2 py-1 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="h-2 w-2 rounded-full bg-gray-400"></div>
                            <span class="text-xs font-semibold text-gray-600">Inactive</span>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.accounting.feature-pricing.edit', $feature) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                    <form action="{{ route('admin.accounting.feature-pricing.destroy', $feature) }}" method="POST" onsubmit="return confirm('Delete this feature pricing?');" class="inline-flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                             <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                             </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-8 flex justify-center">
    {{ $features->links() }}
</div>
@endif
@endsection
