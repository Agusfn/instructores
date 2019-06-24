extends('emails.layout')


@section('content')

<p>{{ $instructor->name }}, acreditamos {{ round($this->reservation->instructor_pay, 2) }} ARS en tu cuenta de saldo de instructor en concepto de las clases de {{ $reservation->sport_discipline }} brindadas en la reserva {{ $reservation->code }}. Podés retirar este saldo a una cuenta bancaria desde tu panel de instructor.
</p>

@endsection