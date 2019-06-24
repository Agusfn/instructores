extends('emails.layout')


@section('content')

<p>
	El pago de la <a href="{{ route('admin.reservations.details', $reservation->id) }}">reserva {{ $reservation->code }}</a> ha recibido un contracargo.
</p>

@endsection