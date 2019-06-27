extends('emails.layout')


@section('content')

<p>{{ $user->name }}, tu reserva {{ $reservation->code }} de clases de {{ $reservation->sport_discipline }} para el {{ $reservation->reserved_class_date->format("d/m/Y") }} entre las {{ $reservation->readableHourRange() }} ha expirado debido a que no se realizó el pago a tiempo.
</p>

@endsection