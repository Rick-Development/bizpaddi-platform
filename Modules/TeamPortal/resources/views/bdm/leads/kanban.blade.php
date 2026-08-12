@extends('teamportal::layouts.app')

@section('title', 'Leads Kanban')

@section('content')
<div class="space-y-6 h-full flex flex-col">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Deal Pipeline</h1>
            <p class="mt-1 text-sm text-gray-500">Drag and drop leads to update their status.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('team.bdm.leads.index') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                List View
            </a>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex-1 overflow-x-auto">
        <div class="flex space-x-4 min-w-max pb-4 h-full" id="kanban-board">
            @foreach($stages as $stage)
                <div class="w-80 flex flex-col bg-gray-50/50 rounded-lg shadow-sm border border-gray-200">
                    <!-- Column Header -->
                    <div class="p-3 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                        <h3 class="font-medium text-gray-900 uppercase tracking-wide text-xs">
                            {{ ucfirst($stage) }}
                            <span class="ml-2 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                {{ isset($leads[$stage]) ? count($leads[$stage]) : 0 }}
                            </span>
                        </h3>
                    </div>

                    <!-- Column Cards Container -->
                    <div class="flex-1 p-3 overflow-y-auto space-y-3 kanban-column" data-status="{{ $stage }}" style="min-height: 200px;">
                        @if(isset($leads[$stage]))
                            @foreach($leads[$stage] as $lead)
                                <div class="kanban-card cursor-grab bg-white p-4 rounded-md shadow-sm border border-gray-200 hover:shadow-md transition-shadow" 
                                     data-id="{{ $lead->id }}">
                                    <div class="flex justify-between items-start">
                                        <p class="text-sm font-medium text-gray-900">{{ $lead->name }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 truncate">{{ $lead->company ?? 'No Company' }}</p>
                                    
                                    <div class="mt-3 flex justify-between items-center text-xs">
                                        <span class="text-green-600 font-medium">
                                            ${{ number_format($lead->value ?? 0, 0) }}
                                        </span>
                                        <span class="text-gray-400">
                                            {{ $lead->created_at->format('M j') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const columns = document.querySelectorAll('.kanban-column');
        
        columns.forEach(column => {
            new Sortable(column, {
                group: 'shared',
                animation: 150,
                ghostClass: 'opacity-50',
                onEnd: function(evt) {
                    const item = evt.item;
                    const leadId = item.getAttribute('data-id');
                    const newStatus = evt.to.getAttribute('data-status');
                    
                    if (evt.from !== evt.to) {
                        updateLeadStatus(leadId, newStatus);
                    }
                }
            });
        });

        function updateLeadStatus(leadId, status) {
            fetch(`/team/bdm/leads/${leadId}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if(!data.success) {
                    alert('Error updating status');
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating status');
                window.location.reload();
            });
        }
    });
</script>
@endpush
