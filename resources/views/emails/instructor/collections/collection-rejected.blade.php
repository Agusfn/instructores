@extends('emails.layout')


@section('content')

<p>{{ $instructor->name }}, tu retiro de dinero de {{ $collection->amount }} ARS solicitado el {{ $collection->created_at->format('d/m/Y') }} ha sido rechazado.<br/>
@if($collection->reject_reason) Motivo: {{ $collection->reject_reason }} @endif<br/>
Intentalo nuevamente.</p>

@endsection