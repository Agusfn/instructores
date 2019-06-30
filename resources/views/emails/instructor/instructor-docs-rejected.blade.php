@extends('emails.layout')


@section('content')

<p>{{ $instructor->name }}, tus documentos proporcionados para aprobar tu cuenta de instructor han sido revisados y no cumplen con lo requerido.<br/>
@if($reason) Motivo: {{ $reason }} <br/> @endif 
Por favor intentalo nuevamente con la documentación correspondiente.</p>

@endsection