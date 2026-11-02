@extends('admin::layouts.app')

@section('title', 'Inquiries')

@section('content')
<div class="sm:flex sm:items-center" x-data="{ 
    selected: [], 
    allSelected: false,
    toggleAll() {
        this.allSelected = !this.allSelected;
        if (this.allSelected) {
            this.selected = {{ $submissions->pluck('id') }};
        } else {
            this.selected = [];
        }
    }
}">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-bold text-white">Contact Inquiries</h1>
        <p class="mt-2 text-sm text-gray-300">View and manage messages from the contact form</p>
    </div>
    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <form action="{{ route('admin.contact-submissions.bulk-delete') }}" method="POST" x-show="selected.length > 0" x-cloak onsubmit="return confirm('Are you sure you want to delete the selected inquiries?');">
            @csrf
            <input type="hidden" name="ids[]" x-model="selected"> <!-- Pass selected IDs here -->
            
            <!-- Hidden inputs for each selected ID to ensure array transmission if x-model fails for some reason, though x-model on select/inputs usually handles it. 
                 Actually, for x-model to work with array name='ids[]', we need to bind strictly. 
                 Better approach: Iterate selected and create hidden inputs. -->
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>

            <button type="submit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto">
                Delete Selected (<span x-text="selected.length"></span>)
            </button>
        </form>
    </div>
</div>

<div class="mt-8 flow-root" x-data="{ 
    selected: [], 
    allSelected: false,
    toggleAll() {
        this.allSelected = !this.allSelected;
        this.selected = this.allSelected ? {{ json_encode($submissions->pluck('id')) }} : [];
    }
}">
    <!-- Duplicate x-data here is tricky. Let's move x-data to a higher wrapper or use a shared parent. 
         Wait, I see I put x-data on the header AND here. That's wrong. I will wrap everything in one div. -->
</div>

<!-- Correct Structure -->
<div x-data="{ 
    selected: [], 
    allSelected: false,
    toggleAll() {
        this.allSelected = !this.allSelected;
        this.selected = this.allSelected ? {{ json_encode($submissions->pluck('id')) }} : [];
    }
}">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-3xl font-bold text-white">Contact Inquiries</h1>
            <p class="mt-2 text-sm text-gray-300">View and manage messages from the contact form</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <form action="{{ route('admin.contact-submissions.bulk-delete') }}" method="POST" x-show="selected.length > 0" x-transition x-cloak onsubmit="return confirm('Are you sure you want to delete the selected inquiries?');">
                @csrf
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto">
                    Delete Selected (<span x-text="selected.length"></span>)
                </button>
            </form>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <!-- Desktop Table View -->
        <div class="hidden md:block -mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-[#10231C]/60/[0.04]">
                            <tr>
                                <th scope="col" class="relative px-7 sm:w-12 sm:px-6">
                                    <input type="checkbox" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-white/10 text-brand-primary focus:ring-brand-primary" 
                                           @click="toggleAll()" :checked="allSelected">
                                </th>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Name</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-white">Subject</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-white">Date</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-white">Status</th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 bg-transparent">
                            @forelse($submissions as $submission)
                            <tr class="{{ $submission->is_read ? '' : 'bg-blue-50/50' }} hover:bg-white/[0.03]">
                                <td class="relative px-7 sm:w-12 sm:px-6">
                                    <input type="checkbox" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-white/10 text-brand-primary focus:ring-brand-primary" 
                                           value="{{ $submission->id }}" x-model="selected">
                                </td>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white sm:pl-6">
                                    {{ $submission->name }}
                                    <div class="text-gray-400 font-normal">{{ $submission->email }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                    {{ Str::limit($submission->subject, 40) }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                    {{ $submission->created_at->format('M d, Y') }}
                                    <div class="text-xs">{{ $submission->created_at->format('g:i a') }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-400">
                                    @if($submission->status === 'new')
                                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">New</span>
                                    @elseif($submission->status === 'in_progress')
                                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-700/10">In Progress</span>
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-white/[0.03] px-2 py-1 text-xs font-medium text-gray-400 ring-1 ring-inset ring-gray-500/10">Closed</span>
                                    @endif
                                    @if(!$submission->is_read)
                                        <span class="ml-1 inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-700/10">Unread</span>
                                    @endif
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('admin.contact-submissions.show', $submission) }}" class="text-brand-primary hover:text-red-900 mr-4">View</a>
                                    <a href="{{ route('admin.contact-submissions.edit', $submission) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                                    <form action="{{ route('admin.contact-submissions.destroy', $submission) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-3 py-8 text-center text-sm text-gray-400">
                                    No inquiries found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            <!-- Mobile Select All -->
            <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-3 flex items-center justify-between">
                <label class="flex items-center space-x-3">
                    <input type="checkbox" class="h-4 w-4 rounded border-white/10 text-brand-primary focus:ring-brand-primary" 
                           @click="toggleAll()" :checked="allSelected">
                    <span class="text-sm font-medium text-gray-300">Select All</span>
                </label>
            </div>

            @forelse($submissions as $submission)
            <div class="{{ $submission->is_read ? 'bg-[#10231C]/60' : 'bg-blue-50' }} rounded-lg shadow-sm border border-white/10 p-4 relative">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" class="h-4 w-4 rounded border-white/10 text-brand-primary focus:ring-brand-primary mt-1" 
                               value="{{ $submission->id }}" x-model="selected">
                        <div>
                            <h3 class="text-base font-semibold text-white">{{ $submission->name }}</h3>
                            <p class="text-xs text-gray-400">{{ $submission->email }}</p>
                        </div>
                    </div>
                     @if(!$submission->is_read)
                        <span class="inline-flex items-center rounded-md bg-red-100 px-2 py-0.5 text-xs font-medium text-red-800">Unread</span>
                    @endif
                </div>

                <div class="ml-7 space-y-2">
                    <p class="text-sm font-medium text-white">{{ Str::limit($submission->subject, 50) }}</p>
                    
                    <div class="flex items-center justify-between text-xs text-gray-400">
                        <span>{{ $submission->created_at->format('M d, Y g:i a') }}</span>
                        @if($submission->status === 'new')
                            <span class="inline-flex items-center rounded-md bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">New</span>
                        @elseif($submission->status === 'in_progress')
                            <span class="inline-flex items-center rounded-md bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">In Progress</span>
                        @else
                            <span class="inline-flex items-center rounded-md bg-white/[0.06] px-2 py-0.5 text-xs font-medium text-white">Closed</span>
                        @endif
                    </div>
                </div>

                <div class="mt-4 flex justify-end gap-3 pt-3 border-t border-white/10 ml-7">
                    <a href="{{ route('admin.contact-submissions.show', $submission) }}" class="text-sm font-medium text-brand-primary hover:text-red-900">View</a>
                    <a href="{{ route('admin.contact-submissions.edit', $submission) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Edit</a>
                    <form action="{{ route('admin.contact-submissions.destroy', $submission) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-900">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="bg-[#10231C]/60 rounded-2xl border border-white/10 backdrop-blur-xl shadow-xl p-8 text-center text-gray-400">
                <p class="text-sm font-medium text-white">No inquiries found.</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <div class="mt-4">
        {{ $submissions->links() }}
    </div>
</div>
@endsection
