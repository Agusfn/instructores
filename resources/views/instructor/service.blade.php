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

	#person2-discount, #person3-discount, #person4-discount, #person5-discount, #person6-discount {
		display: inline-block; 
		width: 80px
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


				@if($errors->has('person2_discount') || $errors->has('person3_discount') || $errors->has('person4_discount') || $errors->has('person5_discount') || $errors->has('person6_discount'))
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					Ingresa todos los valores de descuento grupal correctamente, entre 0 y 100.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				@endif

				@if($errors->has('cant_activate'))
				<div class="alert alert-warning alert-dismissible fade show" role="alert">
					{{ $errors->first('cant_activate') }}
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				@endif

				<h5 style="margin-bottom: 20px">
					Información del servicio <span style="font-size:15px">(<a href="{{ url('instructor/'.$service->number) }}" target="_blank">ver pag</a>)</span>

					@if($service->published)
					<form style="float:right;" action="{{ url('instructor/panel/servicio/pausar') }}" method="POST">
						@csrf
						<button class="btn btn-default">Pausar publicación</button>
					</form>
					@else
					<form style="float:right;" action="{{ url('instructor/panel/servicio/activar') }}" method="POST">
						@csrf
						<button class="btn btn-info">Activar publicación</button>
					</form>
					@endif
					
				</h5>


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

							<input type="hidden" name="worktime_hour_start" value="{{ $service->worktime_hour_start }}" autocomplete="off">
							<input type="hidden" name="worktime_hour_end" value="{{ $service->worktime_hour_end }}" autocomplete="off">
							<input type="hidden" name="worktime_alt_hour_start" value="{{ $service->worktime_alt_hour_start }}" autocomplete="off">
							<input type="hidden" name="worktime_alt_hour_end" value="{{ $service->worktime_alt_hour_end }}" autocomplete="off">

						</div>

						<div class="col-md-2"></div>

						<div class="col-md-5">
							
							<div class="form-group" style="margin-top: 20px">
								<input type="checkbox" id="allow-group-classes" autocomplete="off" name="allow_groups" @if($service->allows_groups) checked @endif>
								<label for="allow-group-classes" style="cursor: pointer;margin-left: 10px;">Permitir clases grupales</label>
							</div>
							<div class="form-group" @if(!$service->allows_groups) style="display: none" @endif>
								<label>Cantidad máx. personas</label>
								<select class="form-control" name="max_group_size" id="max-group-size" autocomplete="off">
									<option @if($service->max_group_size == 2) selected @endif>2</option>
									<option @if($service->max_group_size == 3) selected @endif>3</option>
									<option @if($service->max_group_size == 4) selected @endif>4</option>
									<option @if($service->max_group_size == 5) selected @endif>5</option>
									<option @if($service->max_group_size == 6) selected @endif>6</option>
								</select>
							</div>
							<div class="form-group" @if(!$service->allows_groups) style="display: none" @endif>
								<label>Descuento 2da persona</label><br/>
								<input type="text" class="form-control" name="person2_discount" id="person2-discount" value="{{ $service->person2_discount }}">&nbsp;&nbsp;%
							</div>
							<div class="form-group" @if(!$service->allows_groups || $service->max_group_size < 3) style="display: none" @endif>
								<label>Descuento 3er persona</label><br/>
								<input type="text" class="form-control" name="person3_discount" id="person3-discount" value="{{ $service->person3_discount }}">&nbsp;&nbsp;%
							</div>
							<div class="form-group"@if(!$service->allows_groups || $service->max_group_size < 4) style="display: none" @endif>
								<label>Descuento 4ta persona</label><br/>
								<input type="text" class="form-control" name="person4_discount" id="person4-discount" value="{{ $service->person4_discount }}">&nbsp;&nbsp;%
							</div>
							<div class="form-group"@if(!$service->allows_groups || $service->max_group_size < 5) style="display: none" @endif>
								<label>Descuento 5ta persona</label><br/>
								<input type="text" class="form-control" name="person5_discount" id="person5-discount" value="{{ $service->person5_discount }}">&nbsp;&nbsp;%
							</div>
							<div class="form-group"@if(!$service->allows_groups || $service->max_group_size < 6) style="display: none" @endif>
								<label>Descuento 6ta persona</label><br/>
								<input type="text" class="form-control" name="person6_discount" id="person6-discount" value="{{ $service->person6_discount }}">&nbsp;&nbsp;%
							</div>
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