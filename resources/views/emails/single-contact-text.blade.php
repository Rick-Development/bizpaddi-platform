{{ $subject ?? 'Notification' }}

{!! strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], "\n", $contentBody)) !!}

-- 
{{ config('app.name') }}
{{ config('app.url') }}
