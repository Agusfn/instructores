@extends('emails.layout')


@section('content')

Hola {{ $user->name }},<br/>
<br/>
Hemos recibido una solicitud de reestablecimiento de contraseña de tu cuenta de usuario. Para modificar la contraseña haz <a href="{{ route('user.change-password-form', ['token' => $resetToken, 'email' => $user->email]) }}">click aquí</a>.<br/>
Si no solicitaste un cambio de contraseña por favor ignora este mensaje.

@endsection