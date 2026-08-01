@extends('admin::layouts.app')

@section('title', $contract->contract_number . ' - ' . $contract->title)

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-x-4">
            <a href="{{ route('admin.contracts.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $contract->title }}</h1>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $contract->contract_number }} • 
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $contract->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst(str_replace('_', ' ', $contract->status)) }}
                    </span>
                </p>
            </div>
        </div>
        <div class="flex gap-x-3">
             <a href="{{ route('admin.contracts.docx', $contract) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                <svg class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                Export DOCX
            </a>
            <a href="{{ route('admin.contracts.pdf', $contract) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Download PDF
            </a>
            <a href="{{ route('admin.contracts.edit', $contract) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-[#FB4C4B] hover:bg-[#D93837]">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                Edit Contract
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Contract Content</h3>
            <div class="prose max-w-none">
                {!! $contract->content !!}
            </div>
        </div>

        <!-- Terms -->
        @if($contract->terms)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Terms & Conditions</h3>
            <div class="prose max-w-none text-sm">
                {!! $contract->terms !!}
            </div>
        </div>
        @endif

        <!-- Versions History -->
        @if($contract->versions->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Version History</h2>
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach($contract->versions as $version)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Version <span class="font-medium text-gray-900">{{ $version->version_number }}</span> created</p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        <time datetime="{{ $version->created_at }}">{{ $version->created_at->format('M d, Y H:i') }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Details Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Contract Details</h2>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ strtoupper($contract->contract_type) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $contract->start_date->format('M d, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">End Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $contract->end_date ? $contract->end_date->format('M d, Y') : 'Indefinite' }}
                    </dd>
                </div>
                @if($contract->value)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Value</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">
                        {{ $contract->currency->symbol ?? '$' }}{{ number_format($contract->value, 2) }}
                    </dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500">Party</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <div class="font-medium">{{ $contract->party_name }}</div>
                        <div class="text-gray-500 text-xs">{{ ucfirst($contract->party_type) }}</div>
                        @if($contract->party_email)
                        <div class="text-blue-600 text-xs mt-1">{{ $contract->party_email }}</div>
                        @endif
                    </dd>
                </div>
            </dl>

            @if($contract->status !== 'active' && $contract->status !== 'completed' && $contract->status !== 'terminated')
            <div class="mt-6 pt-6 border-t border-gray-200">
                <form action="{{ route('admin.contracts.status', $contract) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="active">
                    <button type="submit" class="w-full flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        Mark as Active
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Attachments Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Attachments</h2>
                <!-- Upload button could go here -->
            </div>
            
            @if($contract->attachments->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($contract->attachments as $attachment)
                <li class="py-3 flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                        <span class="text-sm text-gray-700 truncate max-w-[150px]">{{ $attachment->file_name }}</span>
                    </div>
                    <a href="#" class="text-xs text-blue-600 hover:text-blue-900">Download</a>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-sm text-gray-500 italic text-center py-4">No attachments</p>
            @endif
        </div>

        <!-- Signatures Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Signatures</h2>
            
            @if($contract->signed_at)
            <div class="bg-green-50 border border-green-200 rounded-md p-3 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Signed</h3>
                        <div class="mt-1 text-xs text-green-700">
                            By {{ $contract->signed_by ?? 'Party' }} on {{ $contract->signed_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-4">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                <button type="button" onclick="document.getElementById('signature-modal').classList.remove('hidden')" class="mt-2 inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-[#FB4C4B] bg-red-100 hover:bg-red-200">
                    Upload Signature
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Signature Modal -->
<div id="signature-modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Signature</h3>
        <form action="{{ route('admin.contracts.signature', $contract) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Signed Document</label>
                <input type="file" name="signature_file" required class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-[#FB4C4B] hover:file:bg-red-100">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Signed Date</label>
                <input type="date" name="signed_at" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#FB4C4B] focus:border-[#FB4C4B] sm:text-sm">
            </div>
            <div class="flex justify-end gap-x-3">
                <button type="button" onclick="document.getElementById('signature-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-[#FB4C4B] text-white rounded-md text-sm font-medium hover:bg-[#D93837]">Upload</button>
            </div>
        </form>
    </div>
</div>
@endsection
