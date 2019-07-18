@extends('emails.layout')


@section('content')

Un usuario ha pagado la reserva <a href="{{ route('admin.reservations.details', $reservation->id) }}">#{{ $reservation->code }}</a> por ${{ $reservation->final_price }} ARS.

@endsection