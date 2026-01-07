{{--@formatter:off--}}
<x-mail::message>
# Nuevo mensaje de {{$data->name}}

<x-mail::panel>
**Nombre:** {{$data->name}}

**Correo electrónico:** {{$data->email}}

@if($data->subject)
**Asunto:** {{$data->subject}}
@endif

**Mensaje:**
{{$data->message}}
</x-mail::panel>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
{{--@formatter:on--}}
