<x-mail::message>
# Nuevo mensaje de {{ $data->name }}

<x-mail::panel>
**Nombre:** {{ $data->name }}<br>
**Correo electrónico:** {{ $data->email }}<br>
@if ($data->subject)
**Asunto:** {{ $data->subject }}<br>
@endif
</x-mail::panel>

{{ $data->message }}

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
