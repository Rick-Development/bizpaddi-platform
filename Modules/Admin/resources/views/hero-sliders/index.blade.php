@extends('superadmin::layouts.app')

@section('title', 'Hero Sliders')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 pb-12">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-400">Homepage Presentation</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Hero Sliders & Banners</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">Manage homepage hero slides and visual carousels.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('super-admin.website-content.index') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-[#0E5E4A] text-white border border-emerald-400/30 hover:bg-[#0c4e3e] shadow-lg transition">
                <span>Edit Live Hero Copy in CMS</span>
                <svg class="w-4 h-4 text-[#F2A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
            <a href="{{ route('admin.hero-sliders.create') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-white/10 text-white hover:bg-white/15 border border-white/10 transition">
                <svg class="w-4 h-4 text-[#F2A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add Slider</span>
            </a>
        </div>
    </div>

    {{-- Live Hero Notice --}}
    <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-5 border border-emerald-500/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <span class="w-10 h-10 rounded-xl bg-emerald-500/15 text-emerald-300 font-black text-sm flex items-center justify-center shrink-0 border border-emerald-500/30">
                ✨
            </span>
            <div>
                <h4 class="text-sm font-bold text-white">Looking to change the live homepage headline or CTA buttons?</h4>
                <p class="text-xs text-gray-400 mt-0.5">The primary homepage hero section on <span class="font-mono text-emerald-400">bizpaddi.com</span> is dynamically managed under Website Copy & CMS.</p>
            </div>
        </div>
        <a href="{{ route('super-admin.website-content.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] transition shrink-0">
            Open Website CMS &rarr;
        </a>
    </div>

    {{-- Sliders Table --}}
    <div class="bg-[#10231C]/90 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10">
                <thead class="bg-black/30">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Subtitle</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($sliders as $slider)
                    <tr class="hover:bg-white/[0.02] transition">
                        <td class="px-6 py-4 text-sm font-semibold text-white">{{ $slider->title }}</td>
                        <td class="px-6 py-4 text-xs text-gray-400">{{ $slider->subtitle }}</td>
                        <td class="px-6 py-4 text-xs font-mono text-gray-300">{{ $slider->order }}</td>
                        <td class="px-6 py-4 text-xs">
                            @if($slider->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-300 border border-emerald-500/30">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-gray-500/10 text-gray-400 border border-white/10">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-xs font-bold space-x-3">
                            <a href="{{ route('admin.hero-sliders.edit', $slider) }}" class="text-emerald-400 hover:text-emerald-300">Edit</a>
                            <form action="{{ route('admin.hero-sliders.destroy', $slider) }}" method="POST" class="inline" onsubmit="return confirm('Delete this hero slider?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-rose-300">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-xs text-gray-400">
                            No secondary sliders created yet. <a href="{{ route('admin.hero-sliders.create') }}" class="text-emerald-400 hover:underline font-bold">Create one</a> or customize the live hero in <a href="{{ route('super-admin.website-content.index') }}" class="text-[#F2A900] hover:underline font-bold">Website Copy & CMS</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
