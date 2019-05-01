@extends('layouts.main')



@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-md-9">

				<a href="{{ route('instructor.account') }}"><- volver a Mi cuenta</a>
				<h4 style="margin-bottom: 20px">Cambiar contraseña</h4>

				@if(\Session::has("success"))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					Contraseña cambiada exitosamente.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				@endif

				<div class="row">
					<div class="col-sm-5">
						<form action="{{ url('instructor/panel/cuenta/password') }}" method="POST">
							@csrf
							<div class="form-group">
								<label>Contraseña actual</label>
								<input type="password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}" name="current_password" value="{{ old('current_password') }}" required>
						    	@if ($errors->has('current_password'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('current_password') }}</strong>
							        </span>
							    @endif
							</div>
							<div class="form-group">
								<label>Nueva contraseña</label>
								<input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
							    @if ($errors->has('password'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('password') }}</strong>
							        </span>
							    @endif
							</div>
							<div class="form-group">
								<label>Repetir contraseña</label>
								<input type="password" class="form-control" name="password_confirmation" required>
							</div>

							<button type="submit" class="btn btn-primary">Cambiar contraseña</button>

						</form>
					</div>
				</div>

			

			</div>

		</div>


	</div>
            
@endsection
