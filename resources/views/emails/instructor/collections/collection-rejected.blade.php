@extends('emails.layout')


@section('content')

<p>
	{{ $instructor->name }}, tu retiro de dinero de {{ $collection->amount }} ARS solicitado el {{ $collection->created_at->format('d/m/Y') }} a tu cuenta @if($collection->isToBank()) bancaria @else de MercadoPago @endif ha sido rechazado.<br/>
	<br/>
	@if($collection->reject_reason) Motivo: {{ $collection->reject_reason }} @endif<br/>
	<br/>
	Intentalo nuevamente.
</p>

@endsection