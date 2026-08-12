@extends('teamportal::layouts.app')

@section('title', 'Edit Campaign')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Campaign</h1>
            <p class="mt-1 text-sm text-gray-600">Update your email marketing campaign</p>
        </div>
        <a href="{{ route('team.bdm.campaigns.show', $campaign) }}" class="text-sm text-gray-600 hover:text-gray-900">
            &larr; Back to Campaign
        </a>
    </div>

    <form action="{{ route('team.bdm.campaigns.update', $campaign) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Campaign Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Campaign Details</h2>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Campaign Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $campaign->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Subject <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" value="{{ old('subject', $campaign->subject) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact List <span class="text-red-500">*</span></label>
                        <select name="contact_list_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                            <option value="">Select a list</option>
                            @foreach($contactLists as $list)
                                <option value="{{ $list->id }}" {{ old('contact_list_id', $campaign->contact_list_id) == $list->id ? 'selected' : '' }}>
                                    {{ $list->name }} ({{ $list->contacts_count ?? $list->total_contacts }} contacts)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Template (Optional)</label>
                        <select name="template_id" id="template-selector" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                            <option value="">Start from scratch</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" data-content="{{ json_encode($template->content) }}" {{ old('template_id', $campaign->template_id) == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sender Info -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Sender Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Name <span class="text-red-500">*</span></label>
                    <input type="text" name="from_name" value="{{ old('from_name', $campaign->from_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Email <span class="text-red-500">*</span></label>
                    <input type="email" name="from_email" value="{{ old('from_email', $campaign->from_email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reply-To Email</label>
                    <input type="email" name="reply_to" value="{{ old('reply_to', $campaign->reply_to) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Email Content <span class="text-red-500">*</span></h2>
            
            <textarea name="content" id="campaign-content" required>{{ old('content', $campaign->content) }}</textarea>
            
            <div class="mt-2 text-sm text-gray-500">
                Available variables: @{{ first_name }}, @{{ last_name }}, @{{ email }}, @{{ company }}
            </div>
        </div>

        <!-- Scheduling -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Scheduling</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled for (Optional)</label>
                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', $campaign->scheduled_at?->format('Y-m-d\TH:i')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FB4C4B] focus:border-transparent">
                        <option value="draft" {{ old('status', $campaign->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="scheduled" {{ old('status', $campaign->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="paused" {{ old('status', $campaign->status) == 'paused' ? 'selected' : '' }}>Paused</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-x-3">
            <a href="{{ route('team.bdm.campaigns.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-[#FB4C4B] text-white rounded-md text-sm font-medium hover:bg-[#D93837]">
                Update Campaign
            </button>
        </div>
    </form>
</div>

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    $('#campaign-content').summernote({
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'hr']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    $('#template-selector').change(function() {
        var content = $(this).find(':selected').data('content');
        if (content) {
            if(confirm('Loading a template will replace existing content. Continue?')) {
                try {
                    var parsed = JSON.parse(content);
                    $('#campaign-content').summernote('code', parsed);
                } catch(e) {
                    $('#campaign-content').summernote('code', content);
                }
            }
        }
    });
});
</script>
@endpush
@endsection
