@extends('instructor.panel.layouts.main-layout')


@section('title', 'Mi servicio')


@section('custom-css')
	@if($instructor->isApproved())
	<link href="{{ asset('resources/vendor/dropzone/min/dropzone.min.css') }}" rel="stylesheet">
	<link href="{{ asset('resources/vendor/nouislider/nouislider.min.css') }}" rel="stylesheet">
	<link href="{{ asset('resources/css/skins/square/blue.css') }}" rel="stylesheet">
	@endif

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

 .sr {background-color: whitesmoke;}.
    #page {background-color: whitesmoke;}
    .mm-slideout { 
        background-color: #299aea!important;
        color: white !important;

    }
    .margin_80_55 {
        background-color: whitesmoke !important;

    }
    
    #registbotton{
        margin-top: 0%;
        margin-bottom: 0%;
        
       
    }

    #ofertas {
        display: none;
    }

    .main_title_3 span em {
    width: 60px;
    height: 2px;
    background-color: #0054a6!important;
    display: block;
}
    .mm-slideout {
        border-bottom: 1px solid #ededed!important;
   
    color: black !important;
}
   .mm-slideout p{
    
    color: black !important;
}
 .mm-slideout   ul > li span > a {
    color: white !important;   
}

.mm-slideout   ul > li span > a:hover {
    color: #fc5b62 !important;   
}

.hamburger-inner, .hamburger-inner::after, .hamburger-inner::before {
    width: 30px;
    height: 4px;
    background-color: #333 !important;
    border-radius: 0;
    position: absolute;
    transition-property: transform;
    transition-duration: .15s;
    transition-timing-function: ease;
}


.dropzone {
	padding: 10px;
}

.dropzone .dz-preview  {
	margin: 13px;
}

.tab-pane {
	background-color: #FFF;
	padding: 30px;
	border: 1px solid #DDD;
	border-top: 0;
}

</style>
	
@endsection




@section('panel-tab-content')
	
	


				@if($instructor->isApproved())

				<div class="row add_bottom_30">
					<div class="col-sm-6">
						<h5>Mi servicio y publicación</h5>
					</div>

					<div class="col-sm-6 text-left text-sm-right">
	                    @if(!$service->paused_by_admin)
							@if($service->published)
							<a href="{{ url('instructor/'.$service->number) }}" target="_blank" class="btn btn-sm btn-primary">Ver publicación</a>

							<form action="{{ url('instructor/panel/servicio/pausar') }}" method="POST" style="display: inline-block;">
								@csrf
								<button type="button" class="btn btn-sm btn-info" onclick="if(confirm('Recuerda guardar los cambios si modificaste alguno. ¿Continuar?')) $(this).parent().submit();">Pausar publicación</button>
							</form>
							@else
							<form action="{{ url('instructor/panel/servicio/activar') }}" method="POST" style="display: inline-block;">
								@csrf
								<button type="button" class="btn btn-sm btn-info" onclick="if(confirm('Recuerda guardar los cambios si modificaste alguno. ¿Continuar?')) $(this).parent().submit();">Activar publicación</button>
							</form>
							@endif
						@endif
					</div>
				</div>
					

				@if(\Session::has("activate-success"))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					Tu publicación se ha activado existosamente. Podés verla <a href="{{ route('service-page', $service->number) }}" target="_blank">desde acá</a>.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				@endif

				@if($errors->has('person2_surcharge') || $errors->has('person3_surcharge') || $errors->has('person4_surcharge') || $errors->has('person5_surcharge') || $errors->has('person6_surcharge'))
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

                @if(!$errors->has('cant_activate') && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif


                @if($service->paused_by_admin)
				<div class="alert alert-info" role="alert">
					Pausamos tu publicación. Corrige la publicación si esta tiene algún problema y comunicate con soporte para poder activarla.
				</div>
                @endif

				<ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Publicación</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Disponibilidad y precios</a>
					</li>
				</ul>
				<div class="tab-content add_bottom_30" id="myTabContent">
					<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
						

						<div class="form-group add_bottom_30">
							<label>Imágenes de portada para tu perfil</label> (una como mínimo)
							<form action="{{ url('instructor/panel/servicio/subir_imagen') }}" method="POST" enctype="multipart/form-data" class="dropzone" id="img-dropzone">
								@csrf
								<div class="fallback">
									<input name="file" type="file" multiple />
								</div>
							</form>
						</div>

						<div class="form-group add_bottom_30">
							<label>Descripción&nbsp;&nbsp;&nbsp;<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Escribe una introducción personal e información de las clases brindadas para que los interesados puedan conocer tu servicio."></i></label>
							<textarea name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" form="service-details" style="height: 130px;">{{ old('description') ?: $service->description }}</textarea>
						    @if ($errors->has('description'))
						        <span class="invalid-feedback" role="alert">
						            <strong>{{ $errors->first('description') }}</strong>
						        </span>
						    @endif
						</div>

						<div class="form-group add_bottom_30">
							<label>Características&nbsp;&nbsp;&nbsp;<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Ingresa características que quieras resaltar de las clases brindadas, separandolas una por linea."></i></label>
							<textarea name="features" class="form-control{{ $errors->has('features') ? ' is-invalid' : '' }}" form="service-details" style="height: 100px;" placeholder="Ej:&#10;Todos los niveles&#10;Todas las edades">{{ old('features') ?: $service->features }}</textarea>
						    @if ($errors->has('features'))
						        <span class="invalid-feedback" role="alert">
						            <strong>{{ $errors->first('features') }}</strong>
						        </span>
						    @endif
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Disciplinas brindadas</label>
									<div style="margin-top: 15px">
										<input type="checkbox" id="instruct-ski" autocomplete="off" name="ski_discipline" form="service-details" @if($service->ski_discipline) checked @endif>
										<label for="instruct-ski" style="cursor: pointer;margin-left: 10px;">Ski</label>
									</div>
									<div style="margin-top: 15px">
										<input type="checkbox" id="instruct-snowboard" autocomplete="off" name="snowboard_discipline" form="service-details" @if($service->snowboard_discipline) checked @endif>
										<label for="instruct-snowboard" style="cursor: pointer;margin-left: 10px;">Snowboard</label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Edades</label>
									<div style="margin-top: 15px">
										<input type="checkbox" id="allow-adults" autocomplete="off" name="allow_adults" form="service-details" @if($service->offered_to_adults) checked @endif>
										<label for="allow-adults" style="cursor: pointer;margin-left: 10px;">Brindar clases a adultos</label>
									</div>

									<div style="margin-top: 15px">
										<input type="checkbox" id="allow-kids" autocomplete="off" name="allow_kids" form="service-details" @if($service->offered_to_kids) checked @endif>
										<label for="allow-kids" style="cursor: pointer;margin-left: 10px;">Brindar clases a niños</label>
									</div>
								</div>
							</div>
						</div>

					</div>
					<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
						
						<form method="POST" action="{{ url('instructor/panel/servicio/guardar_cambios') }}" id="service-details">
							@csrf
							<p>Selecciona una fecha y el precio de la clase. Puedes hacerlo tantas veces quieras para tener diferentes precios por dia si así lo deseas.También puedes crear un rango de fechas y definir tu horario de trabajo.<br> Por ahora las clases estan definidas por bloques de 2hs. </p>

							<div class="row">

								<div class="col-md-6">
									<h6>Fechas de trabajo</h6>
									<table class="table table-sm">
										<thead>
											<tr>
												<th>Fechas</th>
												<th>Precio bloque 2hs</th>
												<th></th>
											</tr>
										</thead>

										<tbody>
											@foreach($service->dateRanges()->orderBy('date_start', 'ASC')->get() as $dateRange)
											<tr>
												<td>{{ $dateRange->date_start->format('d/m/y')." - ".$dateRange->date_end->format('d/m/y') }}</td>
												<td>${{ round($dateRange->price_per_block, 2) }}</td>
												<td>
													<button type="button" class="btn btn-outline-danger btn-sm delete-range-btn" data-range-id="{{ $dateRange->range_id }}">
														<i class="far fa-trash-alt" aria-hidden="true"></i>
													</button>
												</td>
											</tr>
											@endforeach
											<tr id="insert-form-row">
												<td>
													@php($minDate = $activityStartDate->isPast() ? (new Carbon\Carbon())->addDays(1)->format('d/m/y') : $activityStartDate->format('d/m/y'))
													<input type="text" class="form-control" id="date-range-selector" readonly="" style="background-color: #FFF">
													<input type="hidden" class="form-control" id="date_start" value="{{ $minDate }}">
													<input type="hidden" class="form-control" id="date_end" value="{{ $minDate }}">
												</td>
												<td><input type="text" class="form-control" id="block_price"></td>
												<td><button type="button" class="btn btn-outline-success btn-sm" id="btn_submit_range"><i class="fa fa-plus" aria-hidden="true"></i></button></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="col-md-1"></div>
								<div class="col-md-5">
									<h6>Horario de disponibilidad diario</h6>
									<div style="padding: 45px 17px;">
										<div id="hour_slider"></div>
									</div>
									<div style="margin-top: 20px">
										<input type="checkbox" id="separate-working-hours" autocomplete="off" @if($service->hasSplitWorkHours()) checked @endif>
										<label for="separate-working-hours" style="cursor: pointer;margin-left: 10px;">Dividir en dos partes <br> </label><p>(opcional por si quieres tomarte un descanso.)</p>
									</div>

									<input type="hidden" name="worktime_hour_start" value="{{ $service->worktime_hour_start }}" autocomplete="off">
									<input type="hidden" name="worktime_hour_end" value="{{ $service->worktime_hour_end }}" autocomplete="off">
									<input type="hidden" name="worktime_alt_hour_start" value="{{ $service->worktime_alt_hour_start }}" autocomplete="off" @if(!$service->worktime_alt_hour_start) disabled="" @endif>
									<input type="hidden" name="worktime_alt_hour_end" value="{{ $service->worktime_alt_hour_end }}" autocomplete="off" @if(!$service->worktime_alt_hour_end) disabled="" @endif>
								</div>

							</div>

							<hr>
							
							<div class="row">

								<div class="col-md-5">
									
									<div class="form-group" style="margin-top: 15px">
										<input type="checkbox" id="allow-group-classes" autocomplete="off" name="allow_groups" @if($service->allows_groups) checked @endif>
										<label for="allow-group-classes" style="cursor: pointer;margin-left: 10px;">Permitir clases grupales</label>
									</div>
									<div class="form-group" @if(!$service->allows_groups) style="display: none" @endif>
										<label>Cantidad máx. personas</label>
										<select class="form-control" name="max_group_size" id="max-group-size" style="width: 100px" autocomplete="off">
											<option @if($service->max_group_size == 2) selected @endif>2</option>
											<option @if($service->max_group_size == 3) selected @endif>3</option>
											<option @if($service->max_group_size == 4) selected @endif>4</option>
											<option @if($service->max_group_size == 5) selected @endif>5</option>
											<option @if($service->max_group_size == 6) selected @endif>6</option>
										</select>
									</div>

									<table class="table table-sm" id="group-surcharges-table" @if(!$service->allows_groups) style="display: none" @endif>
										<thead>
											<tr>
												<th>Personas</th>
												<th>Recargo total <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Porcentaje de recargo total sobre el precio de la clase en cada caso."></i></th>
											</tr>
										</thead>
										<tbody>
											@for($i=2; $i <= 6; $i++)
											<tr @if(!$service->allows_groups || $service->max_group_size < $i) style="display: none" @endif>
												<td>{{ $i }}</td>
												<td>
													<input type="text" class="form-control form-control-sm" name="person{{ $i }}_surcharge" id="person{{ $i }}-surcharge" value="{{ $service->{'person'.$i.'_surcharge'} }}" style="width: 60px;display:inline">
													%
												</td>
											</tr>
											@endfor
										</tbody>
									</table>

								</div>

								<div class="col-md-1"></div>

								<div class="col-md-6">
									<h6>Horarios específicos ocupados&nbsp;&nbsp;&nbsp;<i class="fa fa-question-circle" style="font-size: 15px" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Asigna horarios de fechas puntuales donde no desees ofrecer clases."></i></h6>
									<table class="table table-sm">
										<thead>
											<tr>
												<th>Fecha</th>
												<th>Horario</th>
												<th></th>
											</tr>
										</thead>

										<tbody>

											@foreach($service->blockedTimeblocks()->orderAsc()->get() as $blockedTimeblock)
											<tr>
												<td>{{ $blockedTimeblock->date->format('d/m/y') }}</td>
												<td>{{ $blockedTimeblock->readableHourRange(true) }}</td>
												<td>
													<button type="button" class="btn btn-outline-danger btn-sm delete-blocked-timeblock-btn" data-blocked-timeblock-id="{{ $blockedTimeblock->id }}">
														<i class="far fa-trash-alt" aria-hidden="true"></i>
													</button>
												</td>
											</tr>
											@endforeach

											<tr id="blocked_timeblock_form_row">
												<td>
													<input type="text" class="form-control" id="blocked_time_block_date" readonly="" style="background-color: #FFF">
												</td>
												<td>
													<select class="form-control" id="blocked_time_block_number" style="width: 150px">
														@for($hourBlock=0; $hourBlock < $blocksPerDay; $hourBlock++)
														<option value="{{ $hourBlock }}">{{ App\Lib\Reservations::blocksToReadableHourRange($hourBlock, $hourBlock, true) }}</option>
														@endfor
													</select>
												</td>
												<td><button type="button" class="btn btn-outline-success btn-sm" id="submit_blocked_time_block"><i class="fa fa-plus" aria-hidden="true"></i></button></td>
											</tr>

										</tbody>
									</table>							
								</div>

							</div>

						</form>


					</div>
				</div>

                <div class="container text-center"><h6>No olvides activar tu publicación una vez que hayas guardado los cambios.</h6></div>
				<div style="margin-top: 40px; text-align: right;">
					<button type="button" id="submit-form-btn" class="btn btn-primary">Guardar cambios</button>
				</div>

				
				@endif


@endsection


@section('custom-js')
	@if($instructor->isApproved())
	<script src="{{ asset('resources/vendor/dropzone/min/dropzone.min.js') }}"></script>
	<script src="{{ asset('resources/vendor/nouislider/nouislider.min.js') }}"></script>
	<script src="{{ asset('resources/js/wNumb.js') }}"></script>
	<script src="{{ asset('resources/js/icheck.min.js') }}"></script>
	<script src="{{ asset('resources/js/instructor-service-pg.js?3') }}"></script>
	<script>
		var app_url = "{{ config('app.url').'/' }}";
		var img_dir = "{{ config('filesystems.disks.public.url').'/img/service/'.$service->number.'/' }}";
		@if($instructor->service->images_json != null)
		var uploaded_imgs = {!! $instructor->service->images_json !!};
		@else
		var uploaded_imgs = [];
		@endif
		var minDate = "{{ $minDate }}";
		var maxDate = "{{ $activityEndDate->format('d/m/y') }}";
	</script>
	@endif
@endsection