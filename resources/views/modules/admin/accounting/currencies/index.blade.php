@extends('admin::layouts.app')

@section('title', 'Currencies')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Currencies</h1>
            <p class="mt-1 text-sm text-gray-600">Manage available currencies for invoices and quotes</p>
        </div>
        <a href="{{ route('admin.accounting.currencies.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors shadow-sm">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add 
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($currencies as $currency)
    <div class="group relative bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col overflow-hidden">
        <!-- Background Decoration -->
        <!-- <div class="absolute -right-8 -top-8 h-12 w-12 rounded-full opacity-5 transition-transform group-hover:scale-150 duration-500 bg-brand-primary"></div> -->
        
        <div class="p-6 flex flex-col flex-1 relative z-10">
            <!-- Header -->
            <div class="flex items-start justify-between mb-4">
                <div class="flex justify-center items-center rounded-full h-12 w-12 bg-brand-primary">
                     <!-- <span class="text-xs font-mono font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-md self-start mb-2">{{ $currency->code }}</span> -->
                     <h3 class="text-xl font-bold text-white line-clamp-1" title="{{ $currency->name }}"> {{ $currency->symbol }}</h3>
                </div>
                <!-- Large Symbol -->
                <div class="h-12 w-12 rounded-2xl bg-gray-50 flex items-center justify-center text-2xl font-bold text-gray-900 group-hover:text-brand-primary group-hover:bg-brand-primary/10 transition-colors">
                   {{ $currency->name }}
                </div>
            </div>

            <!-- Exchange Rate -->
            <div class="mb-6 mt-4">
                <p class="text-sm text-gray-500 mb-1">Exchange Rate</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-gray-900">{{ number_format($currency->exchange_rate, 4) }}</span>
                    @if($currency->is_default)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-brand-primary/10 text-brand-primary">Default</span>
                    @endif
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-auto">
                 <div class="flex items-center gap-2">
                    @if($currency->is_active)
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
                    <a href="{{ route('admin.accounting.currencies.edit', $currency) }}" class="p-2 text-gray-400 hover:text-brand-primary hover:bg-brand-primary/5 rounded-lg transition-colors" title="Edit">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                    @if(!$currency->is_default)
                    <form action="{{ route('admin.accounting.currencies.destroy', $currency) }}" method="POST" onsubmit="return confirm('Delete this currency?');" class="inline-flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                             <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                             </svg>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
