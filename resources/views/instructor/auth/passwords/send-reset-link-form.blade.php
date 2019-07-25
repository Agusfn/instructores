@extends('layouts.main-auth')

@section('title', 'Reestablecer contraseña')


@section('form')


            @if(session('status'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

			<form method="POST" action="{{ route('instructor.reset-password') }}" class="mt-5">
				@csrf
				<div class="form-group">
					<label>Email de la cuenta</label>
					<input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="email" value="{{ old('email') }}" required autocomplete="email">
					<i class="icon_mail_alt"></i>
				    @if ($errors->has('email'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->first('email') }}</strong>
				        </span>
				    @endif
				</div>
				<button type="submit" class="btn_1 rounded full-width">Recuperar contraseña</button>
				<div class="text-center add_top_10"><strong><a href="{{ route('instructor.login') }}">Volver</a></strong></div>
			</form>




@endsection