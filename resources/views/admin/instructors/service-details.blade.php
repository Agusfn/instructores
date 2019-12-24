@extends('admin.layouts.main')


@section('custom-css')
<style type="text/css">

</style>
@endsection



@section('content')

	<!-- Breadcrumbs-->
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Panel</a></li>
			<li class="breadcrumb-item active">Lista de instructores</li>
			<li class="breadcrumb-item active">Detalles de servicio</li>
		</ol>


		<ul class="nav nav-pills margin_bottom" style="margin-bottom: 20px">
			<li class="nav-item">
				<a class="nav-link" href="{{ route('admin.instructors.account-details', $instructor->id) }}">Cuenta</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active" href="{{ route('admin.instructors.service-details', $instructor->id) }}">Servicio y publicación</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ route('admin.instructors.balance-details', $instructor->id) }}">Saldo y dinero</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ route('admin.instructors.review-details', $instructor->id) }}">Reseñas</a>
			</li>
		</ul>


		<div class="box_general padding_bottom">
			
			@if($instructor->isApproved())
			<form action="{{ url('admin/instructores/'.$instructor->id.'/pausa') }}" method="POST" style="display: inline;margin-right: 20px">
				@csrf
				<button type="button" class="btn btn-secondary btn-sm" onclick="if(confirm('¿Confirmar?')) $(this).parent().submit();">
					@if(!$service->paused_by_admin) Pausar publicación @else Quitar pausa publicación @endif
				</button>
			</form>
			@endif

		</div>
		

      	<div class="row">


      		<div class="col-lg-12">

				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Detalles de publicación del servicio 
							@if($instructor->isApproved())
							<a target="_blank" href="{{ route('service-page', $service->number) }}"><i style="font-size: 17px" class="fa fa-link" aria-hidden="true"></i></a>
							@endif
						</h2>
					</div>
					<div class="list_general">
						
						@if($instructor->isApproved())

						<div class="row" style="margin-bottom: 15px">
							<div class="col-lg-4">
								<label><strong>Estado:</strong></label> 
								@if($service->published)
								<span class="badge badge-success" style="font-size: 14px">Publicado</span>
								@else
									@if(!$service->paused_by_admin)
									<span class="badge badge-secondary" style="font-size: 14px">No publicado</span>
									@else
									<span class="badge badge-dark" style="font-size: 14px">Pausado por admin</span>
									@endif
								@endif
							</div>
							<div class="col-lg-4">
								<label><strong>Nro servicio:</strong></label> {{ $service->number }}
							</div>
							<div class="col-lg-4">
								<label><strong>ID servicio:</strong></label> {{ $service->id }}
							</div>
						</div>

						<div class="row" style="margin-bottom: 15px">
							<div class="col-lg-4">
								<label><strong>Snowboard:</strong></label> 
								@if($service->snowboard_discipline)
								Si
								@else
								No
								@endif
							</div>
							<div class="col-lg-4">
								<label><strong>Ski:</strong></label>
								@if($service->ski_discipline)
								Si
								@else
								No
								@endif
							</div>
						</div>

						<div class="row" style="margin-bottom: 15px">
							<div class="col-lg-6">
								<label><strong>Descripción</strong></label>
								<div class="card">
									<div class="card-body">
										@if($service->description)
										{!! nl2br(e($service->description)) !!}
										@else
										<div style="text-align: center;">-</div>
										@endif
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<label><strong>Características</strong></label>
								<div class="card">
									<div class="card-body">
										@if($service->features)
	                                    <ul class="bullets">
	                                        @foreach($service->featuresArray() as $feature)
                                           <li>{{ $feature }}</li>
	                                        @endforeach
	                                    </ul>
                                    	@else
                                		<div style="text-align: center;">-</div>
                                    	@endif
									</div>
								</div>
							</div>
						</div>

						<div>
							<label><strong>Imágenes:</strong></label>
							<div style="text-align: center;">
								@if(sizeof($service->imageUrls()) > 0)
									@foreach($service->imageUrls() as $imgurl)
									<a href="{{ $imgurl['fullsize'] }}" target="_blank"><img src="{{ $imgurl['thumbnail'] }}" style="margin-left: 10px" width="120"></a>
									@endforeach
								@else
									No hay imágenes
								@endif
							</div>
						</div>

						<h5 style="margin: 35px 0 25px">Disponibilidad y precios</h3>

						<div class="row" style="margin-bottom: 15px">
							<div class="col-lg-4">
								<label><strong>Horarios de trabajo:</strong></label>
								De {{ $service->worktime_hour_start }} a {{ $service->worktime_hour_end }}hs
								@if($service->hasSplitWorkHours())
								y de {{ $service->worktime_alt_hour_start }} a {{ $service->worktime_alt_hour_end }}hs
								@endif
							</div>
							<div class="col-lg-4">
								<label><strong>Clases a adultos:</strong></label>
								@if($service->offered_to_adults)
								Si
								@else
								No
								@endif
							</div>
							<div class="col-lg-4">
								<label><strong>Clases a niños:</strong></label>
								@if($service->offered_to_kids)
								Si
								@else
								No
								@endif
							</div>
						</div>

						<div class="row" style="margin-bottom: 15px">
							<div class="col-lg-6">
								<label><strong>Permite clases grupales:</strong></label> @if($service->allows_groups) Sí @else No @endif<br/>
								@if($service->allows_groups)
								<label><strong>Max. personas en grupo:</strong></label> {{ $service->max_group_size }}<br/>
								@endif
							</div>
							@if($service->allows_groups)
							<div class="col-lg-6">
								<label><strong>Recargos por clases grupales:</strong></label><br/>
								@for($i=2; $i <= $service->max_group_size; $i++)
								{{ $i }}º persona: {{ round($service->{'person'.$i.'_surcharge'},2) }}%<br/>
								@endfor
							</div>
							@endif
						</div>

						<h6 style="margin-bottom: 20px">Días de trabajo y precios</h6>
						<table class="table">
							<thead>
								<tr>
									<th>Desde</th>
									<th>Hasta</th>
									<th>Precio bloque 2hs</th>
								</tr>
							</thead>
							<tbody>
								@if($service->dateRanges->count() > 0)
									@foreach($service->dateRanges as $dateRange)
									<tr>
										<td>{{ $dateRange->date_start->format('d/m/Y') }}</td>
										<td>{{ $dateRange->date_end->format('d/m/Y') }}</td>
										<td>${{ round($dateRange->price_per_block, 2) }}</td>
									</tr>
									@endforeach
								@else
								<tr>
									<td colspan="3" style="text-align: center;">Vacio</td>
								</tr>
								@endif
							</tbody>
						</table>
						@else

						<div class="alert alert-warning">El instructor no tuvo su documentación verificada aún. No podrá ofrecer sus servicios hasta que la verifique.</div>
						@endif						
					</div>
				</div>
      		</div>

		</div>
		<!-- /box_general-->
		

@endsection



@section('custom-js')
@endsection