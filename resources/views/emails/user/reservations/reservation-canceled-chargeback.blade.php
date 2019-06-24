extends('emails.layout')


@section('content')

<p>{{ $user->name }}, se canceló tu reserva pendiente {{ $reservation->code }} de clases de {{ $reservation->sport_discipline }} debido a que el pago tuvo un contracargo a la tarjeta de crédito y el dinero ha sido devuelto.
</p>

@endsection