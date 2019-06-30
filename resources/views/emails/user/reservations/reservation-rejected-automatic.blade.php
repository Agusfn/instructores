@extends('emails.layout')


@section('content')

<p>{{ $user->name }}, tu reserva {{ $reservation->code }} fue rechazada automáticamente debido a que el instructor no la confirmó en un lapso de {{ App\Reservation::AUTO_REJECT_TIME_HS }} horas. Tu pago fue reembolsado.
</p>

@endsection