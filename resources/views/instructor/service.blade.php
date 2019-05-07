@extends('layouts.main')


@section('custom-css')
	@if($instructor->isApproved())
	<link href="{{ asset('resources/vendor/dropzone/min/dropzone.min.css') }}" rel="stylesheet">
	<link href="{{ asset('resources/vendor/nouislider/nouislider.min.css') }}" rel="stylesheet">
	<style>
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

			<div class="col-9">

				@if($instructor->isApproved())

				<h5 style="margin-bottom: 20px">Información del servicio</h5>

				<div class="form-group">
					<label>Título del servicio/clases brindadas</label>
					<input type="text" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" form="service-details" placeholder="Ej: Clases de Snowboard para todas las edades" value="{{ old('title') ?: $service->title }}">
				    @if ($errors->has('title'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->first('title') }}</strong>
				        </span>
				    @endif
				</div>
				<div class="form-group">
					<label>Descripción</label>
					<textarea name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" form="service-details">{{ old('description') ?: $service->description }}</textarea>
				    @if ($errors->has('description'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->first('description') }}</strong>
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

					<h6>Horario de disponibilidad diario</h6>
					<div style="height: 100px;padding-top: 45px;width: 300px">
						<div id="hour_slider"></div>
					</div>
					<input type="hidden" name="work_hour_start" value="{{ $service->work_hour_start }}">
					<input type="hidden" name="work_hour_end" value="{{ $service->work_hour_end }}">


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