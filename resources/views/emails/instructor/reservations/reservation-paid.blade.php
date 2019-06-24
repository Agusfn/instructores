@extends('emails.layout')


@section('content')

<p>{{ $instructor->name }}, tenés una nueva reserva (código {{ $reservation->code }}) de clases de {{ $reservation->sport_discipline }} para el {{ $reservation->reserved_class_date->format("d/m/Y") }} entre las {{ $reservation->readableHourRange() }}, y por un total neto de {{ round($reservation->instructor_pay, 2) }} ARS.<br/>
Revisa y confirma la reserva desde tu <a href="{{ route('instructor.reservations') }}">panel de instructor</a>.
</p>

@endsection