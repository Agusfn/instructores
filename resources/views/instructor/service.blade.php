@extends('layouts.main')


@section('custom-css')
	@if($instructor->isApproved())
	<link href="{{ asset('resources/vendor/dropzone/min/dropzone.min.css') }}" rel="stylesheet">
	<link href="{{ asset('resources/vendor/nouislider/nouislider.min.css') }}" rel="stylesheet">
	<link href="{{ asset('resources/css/skins/square/blue.css') }}" rel="stylesheet">
	<style>
	.noUi-connect {
		background: #2489c5;
	}
	.noUi-pips-horizontal {
		max-height: 53px;
	}
	.dz-image img {
		width: 100%;
		height: 100%
	}
	.dropzone .dz-preview:hover .dz-image img {
	  -webkit-filter: blur(0px);
	  filter: blur(0px); 
	}
	.dz-details {
		display: none;
	}
	</style>
	@endif
@endsection




@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-md-9">

				@if($instructor->isApproved())

				<h5 style="margin-bottom: 20px">Información del servicio <span style="font-size:15px">(<a href="{{ url('clases/'.$service->number) }}" target="_blank">ver pag</a>)</span></h5>


				<div class="form-group">
					<label>Descripción&nbsp;&nbsp;&nbsp;<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Escribe una introducción personal e información de las clases brindadas para que los interesados puedan conocer tu servicio."></i></label>
					<textarea name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" form="service-details" style="height: 130px;">{{ old('description') ?: $service->description }}</textarea>
				    @if ($errors->has('description'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->first('description') }}</strong>
				        </span>
				    @endif
				</div>

				<div class="form-group">
					<label>Características&nbsp;&nbsp;&nbsp;<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Ingresa características que quieras resaltar de las clases brindadas, separandolas con saltos de linea."></i></label>
					<textarea name="features" class="form-control{{ $errors->has('features') ? ' is-invalid' : '' }}" form="service-details" style="height: 130px;" placeholder="Ej:&#10;Todos los niveles&#10;Todas las edades">{{ $service->features }}</textarea>
				    @if ($errors->has('features'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->first('features') }}</strong>
				        </span>
				    @endif
				</div>

				<div class="form-group">
					<label>Imágenes de presentación</label>
					<form action="{{ url('instructor/panel/servicio/subir_imagen') }}" method="POST" enctype="multipart/form-data" class="dropzone" id="img-dropzone">
						@csrf
						<div class="fallback">
							<input name="file" type="file" multiple />
						</div>
					</form>
				</div>

				<form method="POST" action="{{ url('instructor/panel/servicio/guardar_cambios') }}" id="service-details">
					@csrf
					<h5 style="margin: 30px 0">Disponibilidad y precios</h5>


					<table class="table table-sm">
						<thead>
							<tr>
								<th>Desde</th>
								<th>Hasta</th>
								<th>Precio bloque 2hs</th>
								<th></th>
							</tr>
						</thead>

						<tbody>
							@foreach($service->dateRanges()->orderBy('date_start', 'ASC')->get() as $dateRange)
							<tr>
								<td>{{ $dateRange->date_start }}</td>
								<td>{{ $dateRange->date_end }}</td>
								<td>{{ $dateRange->price_per_block }}</td>
								<td><button type="button" class="btn btn-danger btn-sm delete-range-btn" data-range-id="{{ $dateRange->id }}"><i class="fa fa-times" aria-hidden="true"></i></button></td>
							</tr>
							@endforeach
							<tr id="insert-form-row">
								<td><input type="text" class="form-control" id="date_start"></td>
								<td><input type="text" class="form-control" id="date_end"></td>
								<td><input type="text" class="form-control" id="block_price"></td>
								<td><button type="button" class="btn btn-success btn-sm" id="btn_submit_range"><i class="fa fa-plus" aria-hidden="true"></i></button></td>
							</tr>
						</tbody>
					</table>

					
					<div class="row">

						<div class="col-md-5">

							<h6>Horario de disponibilidad diario</h6>
							<div style="padding: 45px 0;">
								<div id="hour_slider"></div>
							</div>
							<div style="margin-top: 20px">
								<input type="checkbox" id="separate-working-hours" autocomplete="off" @if($service->hasSplitWorkHours()) checked @endif>
								<label for="separate-working-hours" style="cursor: pointer;margin-left: 10px;">Dividir en dos partes</label>
							</div>

							<input type="hidden" name="work_hours" value="{{ $service->work_hours }}" autocomplete="off">

						</div>

					</div>



					<div class="clearix" style="margin-top: 50px">
						<button type="submit" class="btn btn-primary" style="float: right;">Guardar cambios</button>
					</div>

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
	@if($instructor->isApproved())
	<script src="{{ asset('resources/vendor/dropzone/min/dropzone.min.js') }}"></script>
	<script src="{{ asset('resources/vendor/nouislider/nouislider.min.js') }}"></script>
	<script src="{{ asset('resources/js/wNumb.js') }}"></script>
	<script src="{{ asset('resources/js/icheck.min.js') }}"></script>
	<script src="{{ asset('resources/js/instructor-service-pg.js') }}"></script>
	<script>
		var app_url = "{{ config('app.url').'/' }}";
		var img_dir = "{{ config('filesystems.disks.public.url').'/img/service/'.$service->number.'/' }}";
		@if($instructor->service->images_json != null)
		var uploaded_imgs = {!! $instructor->service->images_json !!};
		@else
		var uploaded_imgs = [];
		@endif
	</script>
	@endif
@endsection