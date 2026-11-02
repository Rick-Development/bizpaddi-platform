@extends('admin::layouts.app')

@section('title', 'Services')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Services</h1>
            <p class="mt-1 text-sm text-gray-400">Manage your service catalog and pricing</p>
        </div>
        <a href="{{ route('admin.accounting.services.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors shadow-sm">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add Service
        </a>
    </div>
</div>

@if($services->isEmpty())
<div class="bg-[#10231C]/60 rounded-2xl border border-white/10 p-12 text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
    </svg>
    <h3 class="mt-4 text-lg font-semibold text-white">No services yet</h3>
    <p class="mt-2 text-sm text-gray-400">Create your first service to start building quotes and invoices.</p>
    <div class="mt-6">
        <a href="{{ route('admin.accounting.services.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-primary text-white rounded-lg hover:bg-brand-primary/90 transition-colors">
            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Create Service
        </a>
    </div>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($services as $service)
    <div class="group relative bg-[#10231C]/60 rounded-3xl hover:shadow-xl hover:border-white/20 transition-all duration-300 border border-white/10 flex flex-col overflow-visible">
        <!-- Floating Price Tag -->
        <div class="absolute -top-3 mb-2 -right-3 z-10">
            <div class="bg-brand-primary text-white text-md font-bold px-4 py-2 rounded-2xl shadow-lg transform group-hover:scale-110 transition-transform duration-300 rotate-2 group-hover:rotate-0">
                {{ $service->currency->symbol ?? '$' }}{{ number_format($service->base_price, 2) }}
                <span class="text-xs font-normal opacity-90">/{{ $service->unit }}</span>
            </div>
        </div>

        <div class="p-6 mt-4 flex flex-col flex-1">
            <!-- Header -->
            <div class="mb-4 pr-12">
                @if($service->category)
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium mb-3 transition-colors" style="background-color: {{ $service->category->color }}15; color: {{ $service->category->color }}">
                     {{ $service->category->name }}
                </span>
                @endif
                <h3 class="text-lg font-bold text-white group-hover:text-brand-primary transition-colors line-clamp-1" title="{{ $service->name }}">{{ $service->name }}</h3>
            </div>

            <!-- Description -->
            <div class="flex-1 mb-6">
                <p class="text-sm text-gray-400 line-clamp-3 leading-relaxed">
                    {{ \Illuminate\Support\Str::limit(trim(html_entity_decode(strip_tags((string) $service->description))) ?: 'No description provided.', 160) }}
                </p>
            </div>

            <!-- Footer Info -->
            <div class="flex items-center justify-between border-t border-white/10 pt-4 mt-auto">
                <div class="flex items-center gap-2">
                    @if($service->is_active)
                        <div class="flex items-center gap-1.5 px-2 py-1 bg-emerald-500/10 rounded-lg border border-emerald-500/20">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            <span class="text-xs font-semibold text-emerald-400">Active</span>
                        </div>
                    @else
                        <div class="flex items-center gap-1.5 px-2 py-1 bg-[#10231C]/60/5 rounded-lg border border-white/10">
                            <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                            <span class="text-xs font-semibold text-gray-400">Inactive</span>
                        </div>
                    @endif
                </div>

                <!-- Action Menu (Hover reveal) -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.accounting.services.edit', $service) }}" class="p-2 text-gray-400 hover:text-brand-primary hover:bg-brand-primary/5 rounded-lg transition-colors" title="Edit">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                    <form action="{{ route('admin.accounting.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Delete this service?');" class="inline-flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-500/10 rounded-lg transition-colors" title="Delete">
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
    {{ $services->links() }}
</div>
@endif
@endsection
