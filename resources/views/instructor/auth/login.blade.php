@extends('layouts.main-auth')


@section('title', 'Login instructor')


@section('form')

			@if(session('verified'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				El email de la cuenta se verificó correctamente. Ya podés iniciar sesión.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			@endif

			<div class="access_social">
				<a href="{{ route('instructor.login.social', 'facebook') }}" class="social_bt facebook">Entrar con Facebook</a>
				<a href="{{ route('instructor.login.social', 'google') }}" class="social_bt google">Entrar con Google</a>
			</div>

            @if($errors->social->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->social->first() }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

			<form method="POST" action="{{ route('instructor.login') }}">
				
				@csrf
				<div class="form-group">
					<label>Email</label>
					<input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" value="{{ old('email') }}" required autocomplete="email">
					<i class="icon_mail_alt"></i>
				    @if ($errors->has('email'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->first('email') }}</strong>
				        </span>
				    @endif
				</div>
				<div class="form-group">
					<label>Contraseña</label>
					<input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required id="password" value="">
					<i class="icon_lock_alt"></i>
				    @if ($errors->has('password'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->first('password') }}</strong>
				        </span>
				    @endif
				</div>
				<div class="clearfix add_bottom_30">
					<div class="checkboxes float-left">
						<label class="container_check">Recordarme
						  <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
						  <span class="checkmark"></span>
						</label>
					</div>
					<div class="float-right mt-1"><a id="forgot" href="{{ route('instructor.reset-password') }}">Olvidaste tu clave?</a></div>
				</div>
				<button type="submit" class="btn_1 rounded full-width">Entrar</button>
				<div class="text-center add_top_10">Nuevo? <strong><a href="{{ route('instructor.register') }}">Registrate!</a></strong></div>
				
			</form>


@endsection