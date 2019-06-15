@extends('layouts.main')

@section('title', 'Modificar cuenta')

@section('content')

	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_80_55">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-md-9">

				<a href="{{ route('instructor.account') }}"><span class="badge badge-pill badge-secondary"><i class="fa fa-arrow-left" aria-hidden="true"></i> volver</span></a>
				<h4 class="add_bottom_30">Mi cuenta</h4>

				<form action="{{ url('instructor/panel/cuenta/modificar') }}" method="POST">
					@csrf

					<div class="more_padding_left add_bottom_60">

						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Nombre</strong>
							</div>
							<div class="col-5">
								<input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ $instructor->name }}" name="name" />
							    @if ($errors->has('name'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('name') }}</strong>
							        </span>
							    @endif
							</div>
						</div>

						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Apellido</strong>
							</div>
							<div class="col-5">
								<input type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" value="{{ $instructor->surname }}" name="surname" />
							    @if ($errors->has('surname'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('surname') }}</strong>
							        </span>
							    @endif
							</div>
						</div>

						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Número de teléfono</strong>
							</div>
							<div class="col-5">
								<input type="text" class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" value="{{ $instructor->phone_number }}" name="phone_number" />
							    @if ($errors->has('phone_number'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('phone_number') }}</strong>
							        </span>
							    @endif
							</div>
						</div>

					</div>
					
					<h4 class="add_bottom_30">Mi perfil</h4>

					<div class="more_padding_left add_bottom_60">
						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Cuenta de instagram</strong>
							</div>
							<div class="col-5">
								<input type="text" class="form-control{{ $errors->has('instagram_username') ? ' is-invalid' : '' }}" value="{{ $instructor->instagram_username }}" name="instagram_username" />
							    @if ($errors->has('instagram_username'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('instagram_username') }}</strong>
							        </span>
							    @endif
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-11">
							<div style="text-align: right;">
								<button class="btn btn-primary">Guardar</button>
							</div>
						</div>	
					</div>
				</form>
			</div>

		</div>


	</div>
            
@endsection
