@extends('emails.layout')


@section('content')

Hola {{ $instructor->name }}!<br/>
<br/>
Muchas gracias por registrarte en <a href="https://instructores.com.ar">Instructores.com.ar</a>! Estás a un paso de ofrecer tus clases a través de la plataforma a una gran cantidad de potenciales clientes. <br/>
<br/>
@if(!$instructor->hasSocialLogin())
Para continuar, por favor verifica el e-mail de tu cuenta haciendo <a href="{{ $verification_url }}">click aquí</a>.
@endif
Sólo te resta subir la documentación que acredite tu identidad en el panel de Instructores para empezar a publicar tus clases, ingresando en el siguiente link: <a href="https://instructores.com.ar/instructor/login">https://instructores.com.ar/instructor/login</a><br/>
<br/>
Te esperamos!

@endsection