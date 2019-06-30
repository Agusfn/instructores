@extends('emails.layout')


@section('content')

<p>{{ $instructor->name }}, la reserva {{ $reservation->code }} que tenías pendiente de confirmación fue rechazada automáticamente por no confirmarla durante {{ App\Reservation::AUTO_REJECT_TIME_HS }} horas. El pago del cliente ha sido reembolsado.
</p>

@endsection