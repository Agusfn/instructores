extends('emails.layout')


@section('content')

<p>{{ $instructor->name }}, se canceló la reserva pendiente {{ $reservation->code }} de clases de {{ $reservation->sport_discipline }} debido a que el pago del cliente tuvo un contracargo y este tuvo de vuelta su dinero. El día y horario reservados se liberaron.
</p>

@endsection