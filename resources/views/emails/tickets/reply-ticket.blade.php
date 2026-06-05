@component('mail::message')

Hi {{$name}}

{{$message}}

@component('mail::button', ['url' => $url])
View Ticket
@endcomponent

Best regards,<br>
**Scribble Team**
@endcomponent
