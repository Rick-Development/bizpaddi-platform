@extends('admin::layouts.app')

@section('title', 'Settings')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white">Site Settings</h1>
        <p class="mt-2 text-sm text-gray-300">Manage global configuration, contact information, and social links.</p>
    </div>
</div>

{{-- Quick Links --}}
<div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
    <a href="{{ route('admin.settings.mail.index') }}"
       class="flex items-center gap-4 rounded-xl border border-white/10 bg-[#10231C]/60 p-4 shadow-sm hover:border-brand-primary hover:shadow-md transition-all group">
        <div class="h-10 w-10 rounded-lg bg-red-50 flex items-center justify-center group-hover:bg-brand-primary transition-colors">
            <svg class="h-5 w-5 text-brand-primary group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-white">Mail / SMTP Settings</p>
            <p class="text-xs text-gray-400">Configure outgoing email server</p>
        </div>
        <svg class="ml-auto h-4 w-4 text-gray-400 group-hover:text-brand-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8 divide-y divide-white/10">
    @csrf
    
    <!-- General Settings -->
    <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
        <div class="sm:col-span-6">
            <h2 class="text-xl font-semibold text-white">General Information</h2>
        </div>
        
        <div class="sm:col-span-6">
            <label for="company_logo" class="block text-sm font-medium text-white">Company Logo</label>
            <div class="mt-2 flex items-center gap-x-3">
                @if(isset($settings['company_logo']))
                    <img src="{{ asset('storage/' . $settings['company_logo']) }}" alt="Company Logo" class="h-12 w-auto object-contain bg-white/[0.03] rounded">
                @endif
                <input type="file" name="company_logo" id="company_logo" accept="image/*"
                       class="block w-full text-sm text-white border border-white/10 rounded-lg cursor-pointer bg-white/[0.03] focus:outline-none bg-[#0a1612] text-white border-white/10">
            </div>
            <p class="mt-1 text-xs text-gray-400">PNG, JPG, GIF up to 2MB</p>
        </div>

        <div class="sm:col-span-6">
            <label for="favicon" class="block text-sm font-medium text-white">Favicon</label>
            <div class="mt-2 flex items-center gap-x-3">
                @if(isset($settings['favicon']))
                    <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Favicon" class="h-8 w-8 object-contain bg-white/[0.03] rounded">
                @endif
                <input type="file" name="favicon" id="favicon" accept="image/*,.ico"
                       class="block w-full text-sm text-white border border-white/10 rounded-lg cursor-pointer bg-white/[0.03] focus:outline-none bg-[#0a1612] text-white border-white/10">
            </div>
            <p class="mt-1 text-xs text-gray-400">PNG, ICO, SVG up to 1MB</p>
        </div>

        <div class="sm:col-span-6">
            <label for="company_signature" class="block text-sm font-medium text-white">Company Signature</label>
            <div class="mt-2 flex items-center gap-x-3">
                @if(isset($settings['company_signature']))
                    <div class="text-xs text-red-500 mb-2">Debug Path: {{ $settings['company_signature'] }}</div>
                    <img src="{{ Storage::url($settings['company_signature']) }}" alt="Company Signature" class="h-12 w-auto object-contain bg-white/[0.03] rounded">
                @endif
                <input type="file" name="company_signature" id="company_signature" accept="image/*"
                       class="block w-full text-sm text-white border border-white/10 rounded-lg cursor-pointer bg-white/[0.03] focus:outline-none bg-[#0a1612] text-white border-white/10">
            </div>
            <p class="mt-1 text-xs text-gray-400">Used for contracts. PNG, JPG, GIF up to 2MB</p>
        </div>
        
        <div class="sm:col-span-6">
            <label for="watermark_logo" class="block text-sm font-medium text-white">Watermark Logo (Optional)</label>
            <div class="mt-2 flex items-center gap-x-3">
                @if(isset($settings['watermark_logo']))
                    <img src="{{ asset('storage/' . $settings['watermark_logo']) }}" alt="Watermark Logo" class="h-12 w-auto object-contain bg-white/[0.03] rounded opacity-50">
                @endif
                <input type="file" name="watermark_logo" id="watermark_logo" accept="image/*"
                       class="block w-full text-sm text-white border border-white/10 rounded-lg cursor-pointer bg-white/[0.03] focus:outline-none bg-[#0a1612] text-white border-white/10">
            </div>
            <p class="mt-1 text-xs text-gray-400">Used for invoice background. PNG, JPG up to 2MB</p>
        </div>

        <div class="sm:col-span-3">
            <label for="company_representative_name" class="block text-sm font-medium text-white">Company Representative Name</label>
            <input type="text" name="company_representative_name" id="company_representative_name" value="{{ $settings['company_representative_name'] ?? 'Authorized Representative' }}"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>

        <div class="sm:col-span-3">
            <label for="site_name" class="block text-sm font-medium text-white">Site Name</label>
            <input type="text" name="site_name" id="site_name" value="{{ $settings['site_name'] ?? config('app.name') }}"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>

        <div class="sm:col-span-3">
            <label for="site_description" class="block text-sm font-medium text-white">Site Description</label>
            <input type="text" name="site_description" id="site_description" value="{{ $settings['site_description'] ?? '' }}"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>
    </div>

    <!-- Contact Information -->
    <div class="pt-8 grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
        <div class="sm:col-span-6">
            <h2 class="text-xl font-semibold text-white">Contact Information</h2>
        </div>

        <div class="sm:col-span-3">
            <label for="contact_email" class="block text-sm font-medium text-white">Contact Email</label>
            <input type="email" name="contact_email" id="contact_email" value="{{ $settings['contact_email'] ?? '' }}"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>

        <div class="sm:col-span-3">
            <label for="contact_phone" class="block text-sm font-medium text-white">Contact Phone</label>
            <input type="text" name="contact_phone" id="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>

        <div class="sm:col-span-6">
            <label for="contact_address" class="block text-sm font-medium text-white">Address</label>
            <textarea name="contact_address" id="contact_address" rows="3"
                      class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">{{ $settings['contact_address'] ?? '' }}</textarea>
        </div>
    </div>

    <!-- Social Media -->
    <div class="pt-8 grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
        <div class="sm:col-span-6">
            <h2 class="text-xl font-semibold text-white">Social Media Links</h2>
        </div>

        <div class="sm:col-span-3">
            <label for="social_linkedin" class="block text-sm font-medium text-white">LinkedIn URL</label>
            <input type="url" name="social_linkedin" id="social_linkedin" value="{{ $settings['social_linkedin'] ?? '' }}"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>

        <div class="sm:col-span-3">
            <label for="social_twitter" class="block text-sm font-medium text-white">Twitter/X URL</label>
            <input type="url" name="social_twitter" id="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>

        <div class="sm:col-span-3">
            <label for="social_facebook" class="block text-sm font-medium text-white">Facebook URL</label>
            <input type="url" name="social_facebook" id="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>

        <div class="sm:col-span-3">
            <label for="social_instagram" class="block text-sm font-medium text-white">Instagram URL</label>
            <input type="url" name="social_instagram" id="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>
    </div>

    <!-- Help & Support Configuration -->
    <div class="pt-8 grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
        <div class="sm:col-span-6">
            <h2 class="text-xl font-semibold text-white">App Help & Support Configuration</h2>
        </div>

        <div class="sm:col-span-2">
            <label for="support_email" class="block text-sm font-medium text-white">Support Email</label>
            <input type="email" name="support_email" id="support_email" value="{{ $settings['support_email'] ?? '' }}"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>

        <div class="sm:col-span-2">
            <label for="support_phone" class="block text-sm font-medium text-white">Support Phone</label>
            <input type="text" name="support_phone" id="support_phone" value="{{ $settings['support_phone'] ?? '' }}"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>
        
        <div class="sm:col-span-2">
             <label for="support_whatsapp" class="block text-sm font-medium text-white">WhatsApp Number</label>
             <input type="text" name="support_whatsapp" id="support_whatsapp" value="{{ $settings['support_whatsapp'] ?? '' }}"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>
    </div>

    <!-- Mobile App Settings -->
    <div class="pt-8 grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
        <div class="sm:col-span-6">
            <h2 class="text-xl font-semibold text-white">Mobile App Settings</h2>
        </div>

        <div class="sm:col-span-6">
             <label for="fcm_server_key" class="block text-sm font-medium text-white">FCM Credentials (Service Account JSON or Server Key)</label>
             <textarea name="fcm_server_key" id="fcm_server_key" rows="6"
                    class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10"
                    placeholder='Paste your "Server Key" OR the entire content of your "service-account.json" file here.'>{{ $settings['fcm_server_key'] ?? '' }}</textarea>
             <p class="mt-2 text-xs text-gray-400">Google recommends using the Service Account JSON (HTTP v1 API). Legacy Server Keys will be deprecated.</p>
        </div>
    </div>

    <!-- Team Access Control -->
    <div class="pt-8 grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
        <div class="sm:col-span-6">
            <h2 class="text-xl font-semibold text-white">Team Access Control</h2>
            <p class="mt-1 text-sm text-gray-400">Customize the messages shown to team members when their account is suspended or inactive.</p>
        </div>

        <div class="sm:col-span-6">
            <label for="suspended_message" class="block text-sm font-medium text-white">Suspension Message</label>
            <textarea name="suspended_message" id="suspended_message" rows="3"
                      class="summernote-editor mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">{{ $settings['suspended_message'] ?? '' }}</textarea>
            <p class="mt-2 text-xs text-gray-400">This message will be shown to users with 'suspended' status.</p>
        </div>

        <div class="sm:col-span-6">
            <label for="inactive_message" class="block text-sm font-medium text-white">Inactivity/Termination Message</label>
            <textarea name="inactive_message" id="inactive_message" rows="3"
                      class="summernote-editor mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">{{ $settings['inactive_message'] ?? '' }}</textarea>
            <p class="mt-2 text-xs text-gray-400">This message will be shown to users with 'inactive' status.</p>
        </div>
    </div>
    
    <!-- Accounting Default Content -->
    <div class="pt-8 grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
        <div class="sm:col-span-6">
            <h2 class="text-xl font-semibold text-white">Accounting Default Content</h2>
            <p class="mt-1 text-sm text-gray-400">Set default notes and terms & conditions that will be pre-filled when creating new quotes or invoices.</p>
        </div>

        <div class="sm:col-span-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-bold text-gray-300 mb-3 border-b pb-1 uppercase tracking-wider">Default Quote Content</h3>
                <div class="space-y-4">
                    <div>
                        <label for="default_quote_notes" class="block text-sm font-medium text-white">Default Quote Notes</label>
                        <textarea name="default_quote_notes" id="default_quote_notes" rows="3"
                                  class="summernote-editor mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">{{ $settings['default_quote_notes'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label for="default_quote_terms" class="block text-sm font-medium text-white">Default Quote Terms & Conditions</label>
                        <textarea name="default_quote_terms" id="default_quote_terms" rows="3"
                                  class="summernote-editor mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">{{ $settings['default_quote_terms'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold text-gray-300 mb-3 border-b pb-1 uppercase tracking-wider">Default Invoice Content</h3>
                <div class="space-y-4">
                    <div>
                        <label for="default_invoice_notes" class="block text-sm font-medium text-white">Default Invoice Notes</label>
                        <textarea name="default_invoice_notes" id="default_invoice_notes" rows="3"
                                  class="summernote-editor mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">{{ $settings['default_invoice_notes'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label for="default_invoice_terms" class="block text-sm font-medium text-white">Default Invoice Terms & Conditions</label>
                        <textarea name="default_invoice_terms" id="default_invoice_terms" rows="3"
                                  class="summernote-editor mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">{{ $settings['default_invoice_terms'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Gateway Settings -->
    <div id="payment-gateway" class="pt-8 grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
        <div class="sm:col-span-6">
            <h2 class="text-xl font-semibold text-white">Payment Gateway Settings (SafeHaven)</h2>
            <p class="mt-1 text-sm text-gray-400">Configure bank details for virtual account settlements.</p>
        </div>

        <div class="sm:col-span-3">
            <label for="safehaven_settlement_bank_code" class="block text-sm font-medium text-white">Settlement Bank Code</label>
            <input type="text" name="safehaven_settlement_bank_code" id="safehaven_settlement_bank_code" value="{{ $settings['safehaven_settlement_bank_code'] ?? '' }}" placeholder="e.g. 090285"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>

        <div class="sm:col-span-3">
            <label for="safehaven_settlement_account_number" class="block text-sm font-medium text-white">Settlement Account Number</label>
            <input type="text" name="safehaven_settlement_account_number" id="safehaven_settlement_account_number" value="{{ $settings['safehaven_settlement_account_number'] ?? '' }}" placeholder="10-digit account number"
                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
        </div>
    </div>

    <div class="pt-8 flex items-center justify-end gap-x-6">
        <button type="button" class="text-sm font-semibold text-white">Cancel</button>
        <button type="submit" class="rounded-md bg-brand-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">Save Settings</button>
    </div>
</form>
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame { border: 1px solid #d1d5db !important; border-radius: 0.5rem !important; overflow: hidden; background: white; }
    .note-toolbar { background: #f9fafb !important; border-bottom: 1px solid #d1d5db !important; }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote-editor').summernote({
            height: 150,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
@endpush
@endsection
