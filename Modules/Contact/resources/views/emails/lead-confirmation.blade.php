@component('mail::message')
# Thank you for reaching out, {{ $lead->name }}!

We have received your project inquiry. Our team at Rick Development is reviewing your requirements, and we will get back to you shortly to discuss how we can help bring your vision to life.

@if(isset($lead->metadata['project_type']))
**Project Details Received:**
- **Type:** {{ $lead->metadata['project_type'] }}
@if(isset($lead->metadata['budget']))
- **Budget:** {{ $lead->metadata['budget'] }}
@endif
@if(isset($lead->metadata['timeline']))
- **Timeline:** {{ $lead->metadata['timeline'] }}
@endif
@endif

We look forward to speaking with you soon!

Best regards,<br>
The Team at {{ config('app.name') }}
@endcomponent
