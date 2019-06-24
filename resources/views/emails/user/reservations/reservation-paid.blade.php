@extends('emails.layout')


@section('content')

<p>{{ $user->name }}, se acreditó tu pago por la reserva código {{ $reservation->code }} de clases de {{ $reservation->sport_discipline }} para el {{ $reservation->reserved_class_date->format("d/m/Y") }} entre las {{ $reservation->readableHourRange() }}, y por un total de {{ round($reservation->final_price, 2) }} ARS.<br/>
El instructor te confirmará la reserva dentro de las próximas {{ App\Reservation::AUTO_REJECT_TIME_HS }} horas, te lo avisaremos por e-mail.
</p>

@endsection