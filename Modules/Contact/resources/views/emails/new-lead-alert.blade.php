@component('mail::message')
# New Project Lead Received

A new lead has been submitted via the website.

**Contact Information:**
- **Name:** {{ $lead->name }}
- **Email:** {{ $lead->email }}
@if($lead->phone)
- **Phone:** {{ $lead->phone }}
@endif
@if($lead->company)
- **Company:** {{ $lead->company }}
@endif

**Project Details:**
- **Type:** {{ $lead->metadata['project_type'] ?? 'N/A' }}
- **Budget:** {{ $lead->metadata['budget'] ?? 'N/A' }}
- **Timeline:** {{ $lead->metadata['timeline'] ?? 'N/A' }}

@if($lead->notes)
**Additional Notes:**
{{ $lead->notes }}
@endif

@component('mail::button', ['url' => url('/admin/leads/' . $lead->id)])
View Lead in Admin Panel
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
