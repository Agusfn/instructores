@extends('layouts.main')


@section('custom-css')
<link href="{{ asset('resources/vendor/dropzone/min/dropzone.min.css') }}" rel="stylesheet">
@endsection




@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-9">

				@if(Auth::user()->isApproved())

				<h5 style="margin-bottom: 20px">Información del servicio</h5>

				<div class="form-group">
					<label>Título del servicio/clases brindadas</label>
					<input type="text" class="form-control" placeholder="Ej: Clases de Snowboard para todas las edades">
				</div>
				<div class="form-group">
					<label>Descripción</label>
					<textarea class="form-control"></textarea>
				</div>

				<div class="form-group">
					<label>Imágenes de presentación</label>
					<form action="/file-upload" class="dropzone" id="my-awesome-dropzone"></form>
				</div>

				<form>
					<h5 style="margin: 30px 0">Disponibilidad y precios</h5>

					<div class="row">
						<div class="col-md-6">

							<div class="form-group">
								<label>Días de la semana de disponibilidad</label><br/>
								

								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="monday" name="monday">
									<label class="form-check-label" for="monday">Lunes</label>
								</div>

								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="tuesday" name="tuesday">
									<label class="form-check-label" for="tuesday">Martes</label>
								</div>

								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="wednesday" name="wednesday">
									<label class="form-check-label" for="wednesday">Miércoles</label>
								</div>

								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="thursday" name="thursday">
									<label class="form-check-label" for="thursday">Jueves</label>
								</div>

								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="friday" name="friday">
									<label class="form-check-label" for="friday">Viernes</label>
								</div>

								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="saturday" name="saturday">
									<label class="form-check-label" for="saturday">Sábado</label>
								</div>

								<div class="form-check">
									<input type="checkbox" class="form-check-input" id="sunday" name="sunday">
									<label class="form-check-label" for="sunday">Domingo</label>
								</div>

							</div>

						</div>

						<div class="col-md-6">

							<div class="form-group">
								<label>Precio bloque 2hs temporada baja</label><br/>
								<input type="text" class="form-control" style="width: 100px; display: inline-block;"> ARS
							</div>
							<div class="form-group">
								<label>Precio bloque 2hs temporada media</label><br/>
								<input type="text" class="form-control" style="width: 100px; display: inline-block;"> ARS
							</div>
							<div class="form-group">
								<label>Precio bloque 2hs temporada alta</label><br/>
								<input type="text" class="form-control" style="width: 100px; display: inline-block;"> ARS
							</div>

						</div>
					</div>

					<button type="submit" class="btn btn-primary">Publicar servicio</button>
					

				</form>

				@else
				<div class="alert alert-warning">
					Tu cuenta no ha sido aprobada aún. Para empezar a ofrecer tus servicios debés verificar tu documentación de identidad y certificación.
				</div>
				@endif
			
			</div>

		</div>


	</div>
            
@endsection


@section('custom-js')
<script src="{{ asset('resources/vendor/dropzone/min/dropzone.min.js') }}"></script>
@endsection