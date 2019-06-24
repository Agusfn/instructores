@extends('emails.layout')


@section('content')

<p>
	{{ $user->name }}, tu reserva {{ $reservation->code }} de clases de {{ $reservation->sport_discipline }} para el {{ $reservation->reserved_class_date->format("d/m/Y") }} entre las {{ $reservation->readableHourRange() }}, ha sido confirmada por el instructor.<br/>
	@if($reservation->confirm_message)
	El instructor te ha dejado un mensaje: <span style="font-style: italic;">{{ $reservation->confirm_message }}</span><br/>
	@endif
	Podés ver los <a href="{{ route('user.reservation', $reservation->code) }}">detalles de la reserva</a> en tu panel de usuario.
</p>

@endsection