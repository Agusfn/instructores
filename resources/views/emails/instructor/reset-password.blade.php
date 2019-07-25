@extends('emails.layout')


@section('content')

Hola {{ $instructor->name }},<br/>
<br/>
Hemos recibido una solicitud de reestablecimiento de contraseña de tu cuenta de instructor. Para modificar la contraseña haz <a href="{{ route('instructor.change-password-form', ['token' => $resetToken, 'email' => $instructor->email]) }}">click aquí</a>.<br/>
Si no solicitaste un cambio de contraseña por favor ignora este mensaje.

@endsection