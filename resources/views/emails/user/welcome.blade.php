@extends('emails.layout')


@section('content')

Hola {{ $user->name }}!<br/>
<br/>
Queremos darte la bienvenida a <a href="https://instructores.com.ar">Instructores.com.ar</a>.<br/>
<br/>
Por favor no olvides leer las <a href="{{ route('faq') }}">condiciones del servicio</a> antes de contratar.<br/>
<br/>
Estamos para ayudarte ante cualquier consulta.<br/>
<br/>
Un saludo cordial.

@endsection