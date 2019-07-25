@extends('layouts.main-auth')

@section('title', 'Reestablecer contraseña')


@section('form')


			<form method="POST" action="{{ route('user.change-password') }}" class="mt-5">
				@csrf
				<input type="hidden" name="token" value="{{ $token }}">
				<div class="form-group">
					<label>E-mail</label>
					<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email">
					<i class="icon_mail_alt"></i>
					@if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
				</div>
				<div class="form-group">
					<label>Contraseña</label>
					<input id="password1" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required autocomplete="new-password">
					<i class="icon_lock_alt"></i>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
				</div>
				<div class="form-group">
					<label>Confirmar contraseña</label>
					<input id="password2" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
					<i class="icon_lock_alt"></i>
				</div>


				<button type="submit" class="btn_1 rounded full-width">Cambiar contraseña</button>
				<div class="text-center add_top_10"><strong><a href="{{ route('user.login') }}">Volver</a></strong></div>
			</form>




@endsection