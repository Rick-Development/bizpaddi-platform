@extends('teamportal::layouts.app')

@section('title', 'Services')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6">
  <div class="sm:flex sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Services</h1>
      <p class="mt-1 text-xs sm:text-sm text-gray-400">Available services offered.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('team.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-300 transition-colors">
            &larr; Back to Dashboard
        </a>
    </div>
  </div>

  <div class="bg-[#10231C]/60 backdrop-blur-sm rounded-3xl border border-white/10 shadow-2xl overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-white/5 text-left text-xs sm:text-sm">
        <thead class="bg-white/5">
          <tr>
            <th scope="col" class="py-4 pl-6 pr-3 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Name</th>
            <th scope="col" class="px-4 py-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Price</th>
            <th scope="col" class="px-4 py-4 font-bold text-gray-400 uppercase tracking-wider text-[11px]">Status</th>
            <th scope="col" class="relative py-4 pl-3 pr-6 text-right">
              <span class="sr-only">View</span>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
          @forelse($services as $service)
          <tr class="hover:bg-white/[0.03] transition-colors">
            <td class="whitespace-nowrap py-4 pl-6 pr-3 font-bold text-white">{{ $service->name }}</td>
            <td class="whitespace-nowrap px-4 py-4 font-black text-white">${{ number_format($service->price, 2) }}</td>
            <td class="whitespace-nowrap px-4 py-4">
              <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider {{ $service->is_active ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30' : 'bg-red-500/10 text-red-400 border border-red-500/30' }}">
                  {{ $service->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right font-medium">
              <a href="{{ route('team.services.show', $service) }}" class="text-emerald-400 hover:text-emerald-300 font-bold text-xs">View &rarr;</a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="px-6 py-12 text-center text-xs text-gray-400 italic">No services found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($services->hasPages())
    <div class="p-4 border-t border-white/10 bg-white/[0.02]">
         {{ $services->links() }}
    </div>
    @endif
  </div>
</div>
@endsection
