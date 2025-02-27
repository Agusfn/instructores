@extends('layouts.main-auth')

@section('title', 'Registrarse')

@section('form')


		
			@if(session('registered'))
			<div class="alert alert-success">
				La cuenta se registró exitosamente. Revisa tu casilla de e-mail para verificar tu cuenta y poder iniciar sesión.
			</div>
			@else
			<form autocomplete="off" method="POST" action="{{ route('user.register') }}">
				@csrf
				<div class="form-group">
					<label>Nombre</label>
					<input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
					<i class="ti-user"></i>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
				</div>
				<div class="form-group">
					<label>Apellido</label>
					<input id="surname" type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname') }}" required autocomplete="surname" autofocus>
					<i class="ti-user"></i>
                    @if ($errors->has('surname'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('surname') }}</strong>
                        </span>
                    @endif
				</div>
				<div class="form-group">
					<label>E-mail</label>
					<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autocomplete="email">
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
				<div id="pass-info" class="clearfix"></div>
				<button type="submit" class="btn_1 rounded full-width add_top_30">Registrarse ahora!</button>
				<div class="text-center add_top_10">¿Ya tenés una cuenta? <strong><a href="{{ route('user.login') }}">Iniciar sesión</a></strong></div>
			</form>
			@endif




@endsection


@section('custom-js')
	<!-- SPECIFIC SCRIPTS -->
	<script src="{{ asset('resources/js/pw_strenght.js') }}"></script>
@endsection