@extends('teamportal::layouts.app')

@section('title', 'Service Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ $service->name }}</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">Service specification and pricing</p>
        </div>
        <div>
            <a href="{{ route('team.services.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs sm:text-sm font-semibold text-gray-300 hover:bg-white/10 hover:text-white transition-all shadow-sm">
                &larr; Back to Services
            </a>
        </div>
    </div>

    <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/10 p-6 sm:p-8">
        <dl class="divide-y divide-white/5 text-xs sm:text-sm">
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="font-bold text-gray-400 uppercase tracking-wider text-[11px]">Description</dt>
                <dd class="mt-1 text-gray-300 sm:col-span-2 sm:mt-0 leading-relaxed">{{ $service->description }}</dd>
            </div>
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="font-bold text-gray-400 uppercase tracking-wider text-[11px]">Price</dt>
                <dd class="mt-1 text-emerald-400 font-black text-lg sm:col-span-2 sm:mt-0">${{ number_format($service->price, 2) }}</dd>
            </div>
            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="font-bold text-gray-400 uppercase tracking-wider text-[11px]">Status</dt>
                <dd class="mt-1 sm:col-span-2 sm:mt-0">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $service->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/10 text-red-400 border border-red-500/30' }}">
                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </dd>
            </div>
        </dl>
    </div>
</div>
@endsection
