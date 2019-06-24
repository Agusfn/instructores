extends('emails.layout')


@section('content')

<p>{{ $user->name }}, tu reserva {{ $reservation->code }} de clases de {{ $reservation->sport_discipline }} para el {{ $reservation->reserved_class_date->format("d/m/Y") }} ha sido cancelada.<br/>
	@if($reason)
	Motivo: {{ $reason }} <br/>
	@endif
	Cualquier pago que hubiera habido ha sido reembolsado.
</p>

@endsection