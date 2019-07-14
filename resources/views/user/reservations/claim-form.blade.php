@extends('layouts.main')

@section('title', 'Reserva')


@section('custom-css')

@endsection

@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

	
			@include('user.panel-sidebar')

			<div class="col-lg-9">

				<h4 class="add_bottom_30">Crear reclamo</h4>

				<div class="row">

					<div class="col-md-7">
						
						<form action="{{ route('user.reservations.claim', $reservation->code) }}" method="POST">
							@csrf
							<div class="form-group">
								<label>Motivo del reclamo</label>
								<select class="form-control{{ $errors->has('motive') ? ' is-invalid' : '' }}" name="motive">
									<option>asdf</option>
								</select>
							    @if ($errors->has('motive'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('motive') }}</strong>
							        </span>
							    @endif
							</div>


							<div class="form-group">
								<label>Detalles del reclamo</label>
								<textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description"></textarea>
							    @if ($errors->has('description'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('description') }}</strong>
							        </span>
							    @endif
							</div>

							<button class="btn btn-primary">Enviar</button>

						</form>

					</div>

				</div>
				

			</div>

		</div>


	</div>
            
@endsection


@section('body-end')

@endsection


@section('custom-js')


@endsection