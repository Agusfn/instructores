@extends('emails.layout')


@section('content')

<p>Gracias por registrarte {{ $user->name }}, presioná <a href="{{ $verification_url }}">acá</a> para registrar tu cuenta.</p>

@endsection