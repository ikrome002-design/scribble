<x-mail::message>

Dear {{ $name }},<br>

@if(is_array($message))
@foreach($message as $m)
{{ $m }}<br>

@endforeach
@else
{{ $message }}
@endif

@if($code)    
<x-mail::panel>
{{ $code }}
</x-mail::panel>
@endif

@if(count($userData)>0)    
<x-mail::panel>
@foreach($userData as $k=>$v)
{{ $k }} : {{$v}}<br>
@endforeach
</x-mail::panel>
@endif

@if($url)
<x-mail::button :url="$url">
{{ $anchor ?? 'Click Here' }}
 </x-mail::button>
@endif

Best regards,<br>
**Scribble Team**
</x-mail::message>
