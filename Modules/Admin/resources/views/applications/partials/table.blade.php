@forelse($applications as $application)
<tr>
    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
        <div class="font-medium">{{ $application->candidate->name ?? ($application->candidate->first_name . ' ' . $application->candidate->last_name) }}</div>
        <div class="text-gray-500">{{ $application->candidate->email }}</div>
    </td>
    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
        {{ $application->job->title }}
    </td>
    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
        {{ $application->created_at->format('M d, Y') }}
    </td>
    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
        @php
            $statusClasses = [
                'pending' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                'reviewing' => 'bg-blue-50 text-blue-700 ring-blue-700/10',
                'interview' => 'bg-purple-50 text-purple-700 ring-purple-700/10',
                'accepted' => 'bg-green-50 text-green-700 ring-green-600/20',
                'rejected' => 'bg-red-50 text-red-700 ring-red-600/10',
            ];
        @endphp
        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusClasses[$application->status] ?? 'bg-gray-50 text-gray-600 ring-gray-500/10' }}">
            {{ ucfirst($application->status) }}
        </span>
    </td>
    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
        @if($application->ai_score)
            <span class="font-semibold {{ $application->ai_score >= 80 ? 'text-green-600' : ($application->ai_score >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                {{ $application->ai_score }}%
            </span>
        @else
            <span class="text-gray-400">-</span>
        @endif
    </td>
    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
        <a href="{{ route('admin.applications.show', $application) }}" class="text-brand-primary hover:text-red-900">View Details</a>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="px-3 py-8 text-center text-sm text-gray-500">
        No applications found.
    </td>
</tr>
@endforelse
