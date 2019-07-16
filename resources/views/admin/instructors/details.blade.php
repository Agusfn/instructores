@extends('admin.layouts.main')


@section('custom-css')
<style type="text/css">
	.profile-pic
	{
		width: 100%;
		max-width: 200px;
		border-top-left-radius: 50% 50%;
		border-top-right-radius: 50% 50%;
		border-bottom-right-radius: 50% 50%;
		border-bottom-left-radius: 50% 50%;
	}
</style>
@endsection


@section('body-start')


@if(!$instructor->isApproved())
<div class="modal" tabindex="-1" role="dialog" id="approval-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Aprobar instructor</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="alert alert-info">Ingresa los datos de los documentos enviados por el instructor.</div>

				<form action="{{ url('admin/instructores/'.$instructor->id.'/aprobar') }}" method="POST" id="approve-form">
					@csrf
					<div class="form-group">
						<label>Tipo de documento</label>
						<select name="identification_type" class="form-control{{ $errors->approval->has('identification_type') ? ' is-invalid' : '' }}">
							@foreach(App\Instructor::$identification_types as $code => $name)
							<option value="{{ $code }}">{{ $name }}</option>
							@endforeach
						</select>
						@if ($errors->approval->has('identification_type'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->approval->first('identification_type') }}</strong>
				        </span>
				    	@endif
					</div>
					<div class="form-group">
						<label>Número de documento</label>
						<input type="text" class="form-control{{ $errors->approval->has('identification_number') ? ' is-invalid' : '' }}" name="identification_number">
						@if ($errors->approval->has('identification_number'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->approval->first('identification_number') }}</strong>
				        </span>
				    	@endif
					</div>
					<div class="form-group">
						<label>Nivel de instructor (1-5)</label>
						<input type="text" class="form-control{{ $errors->approval->has('level') ? ' is-invalid' : '' }}" name="level" placeholder="Dejar vacío en caso de no querer registrar el nivel">
						@if ($errors->approval->has('level'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->approval->first('level') }}</strong>
				        </span>
				    	@endif
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="if(confirm('¿Aprobar instructor?')) $('#approve-form').submit();">Confirmar</button>
			</div>
		</div>
	</div>
</div>

	@if($instructor->approvalDocsSent())
	<div class="modal" tabindex="-1" role="dialog" id="reject-docs-modal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Rechazar documentación</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<form action="{{ url('admin/instructores/'.$instructor->id.'/rechazar_doc') }}" method="POST" id="reject-docs-form">
						@csrf
						<div class="form-group">
							<label>Motivo del rechazo de documentación</label>
							<input type="text" class="form-control{{ $errors->doc_rejectal->has('reason') ? ' is-invalid' : '' }}" name="reason">
							@if ($errors->doc_rejectal->has('reason'))
					        <span class="invalid-feedback" role="alert" style="display: block;">
					            <strong>{{ $errors->doc_rejectal->first('reason') }}</strong>
					        </span>
					    	@endif
						</div>
					</form>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" onclick="if(confirm('¿Rechazar documentacion?')) $('#reject-docs-form').submit();">Confirmar</button>
				</div>
			</div>
		</div>
	</div>
	@endif
@endif



@endsection




@section('content')

	<!-- Breadcrumbs-->
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Panel</a></li>
			<li class="breadcrumb-item active">Lista de instructores</li>
			<li class="breadcrumb-item active">Detalles de instructor</li>
		</ol>

		@if($errors->delete_instructor->any())
		<div class="alert alert-danger">
			{{ $errors->delete_instructor->first() }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
		</div>
		@endif


		<div class="box_general padding_bottom">

			@if(!$instructor->isApproved())
			<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#approval-modal">Aprobar instructor</button>
				@if($instructor->approvalDocsSent())
				<button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#reject-docs-modal" style="margin-right: 30px">Rechazar documentación</button>
				@endif
			@endif
			
			<form action="{{ url('admin/instructores/'.$instructor->id.'/suspender') }}" method="POST" style="display: inline;margin-right: 30px">
				@csrf
				@if(!$instructor->suspended)
				<button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('¿Suspender cuenta? No podrá iniciar sesión.')) $(this).parent().submit();">Suspender cuenta</button>
				@else
				<button type="button" class="btn btn-info btn-sm" onclick="if(confirm('¿Reahabilitar cuenta?')) $(this).parent().submit();">Habilitar cuenta</button>
				@endif
			</form>

			@if($instructor->reservations()->count() == 0)
			<form action="{{ url('admin/instructores/'.$instructor->id.'/eliminar') }}" method="POST" style="display: inline;">
				@csrf
				<button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('¿ELIMINAR cuenta? No se podrá recuperar')) $(this).parent().submit();">Eliminar cuenta</button>
			</form>
			@endif





		</div>

      	<div class="row">

      		<div class="col-lg-6">
				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Datos personales y de la cuenta</h2>
					</div>
					<div class="list_general">

						<div class="row" style="margin: 20px 0 40px 0">
							

							<div class="col-md-3">
								@if($instructor->profile_picture)
									<img src="{{ $instructor->getProfilePicUrl() }}" class="profile-pic">
								@else
									<img src="{{ asset('resources/admin/img/avatar.jpg') }}" class="profile-pic">
								@endif
							</div>

							<div class="col-md-9">
								<div class="row" style="margin-bottom: 20px">
									<div class="col-md-6">
										<label><strong>Nombre y apellido</strong></label><br/>
										{{ $instructor->name.' '.$instructor->surname }}
									</div>
									<div class="col-md-6">
										<label><strong>E-mail</strong></label><br/>
										{{ $instructor->email }}
									</div>
								</div>

								<div class="row" style="margin-bottom: 20px">
									<div class="col-md-6">
										<label><strong>Nro tel</strong></label><br/>
										@if($instructor->phone_number)
											{{ $instructor->phone_number }}
										@else
											-
										@endif
									</div>
									<div class="col-md-6">
										<label><strong>Cta instagram</strong></label><br/>
										@if($instructor->instagram_username)
										<a href="https://instagram.com/{{ $instructor->instagram_username }}" target="_blank">{{ $instructor->instagram_username }}</a>
										@else
										-
										@endif
									</div>
								</div>


							</div>
						</div>


						<div class="row" style="margin-bottom: 20px">
							<div class="col-md-3">
								<label><strong>Aprobación</strong></label><br/>
								@if(!$instructor->isApproved())
									@if(!$instructor->approvalDocsSent())
										<span class="badge badge-dark">Envío doc. pendiente</span>
									@else
										<span class="badge badge-warning">Docs enviados. Revisar</span>
									@endif
								@else
									<span class="badge badge-success">Aprobado</span>
								@endif
							</div>
							<div class="col-md-3">
								<label><strong>Fotos certif.</strong></label><br/>
								@if($instructor->approvalDocsSent())

									@foreach(explode(',', $instructor->professional_cert_imgs) as $fileName)
										<a href="{{ route('admin.instructors.documents', ['id' => $instructor->id, 'filename' => $fileName]) }}" target="_blank">Imágen {{ ($loop->index+1) }}</a><br/>
									@endforeach

								@else
									-
								@endif
							</div>
							<div class="col-md-3">
								<label><strong>Fotos documento</strong></label><br/>
								@if($instructor->approvalDocsSent())

									@foreach(explode(',', $instructor->identification_imgs) as $fileName)
										<a href="{{ route('admin.instructors.documents', ['id' => $instructor->id, 'filename' => $fileName]) }}" target="_blank">Imágen {{ ($loop->index+1) }}</a><br/>
									@endforeach

								@else
									-
								@endif
							</div>
							<div class="col-md-3">
								@if(!$instructor->isApproved())
									<label><strong>Fecha enviados</strong></label><br/>
									@if($instructor->approvalDocsSent())
										{{ date('d/m/Y H:i:s', strtotime($instructor->documents_sent_at)) }}
									@else
										-
									@endif
								@else
									<label><strong>Fecha aprobado</strong></label><br/>
									{{ date('d/m/Y', strtotime($instructor->approved_at)) }}
								@endif
							</div>
						</div>

						<div class="row" style="margin-bottom: 20px">
							<div class="col-md-3">
								<label><strong>Tipo documento</strong></label><br/>
								@if($instructor->isApproved())
									{{ App\Instructor::idTypeName($instructor->identification_type) }}
								@else
									-
								@endif
							</div>

							<div class="col-md-3">
								<label><strong>Nro. documento</strong></label><br/>
								@if($instructor->isApproved())
									{{ $instructor->identification_number }}
								@else
									-
								@endif
							</div>

							<div class="col-md-3">
								<label><strong>Nivel instructor</strong></label><br/>
								@if($instructor->isApproved() && $instructor->level)
									{{ $instructor->level }}
								@else
									-
								@endif
							</div>
						</div>

					</div>
				</div>


				<div class="box_general padding_bottom">
					<div class="header_box">
						<h6 class="d-inline-block">Datos de la cuenta</h6>
					</div>
					<div class="list_general">

						<div class="row" style="margin: 10px 0 10px 0">
							<div class="col-md-3">
								<label><strong>ID instructor</strong></label><br/>
								{{ $instructor->id }}
							</div>
							<div class="col-md-3">
								<label><strong>Registrado el</strong></label><br/>
								@if($instructor->created_at)
								{{ $instructor->created_at->format('d/m/Y') }}
								@else
								-
								@endif
							</div>
							<div class="col-md-3">
								<label><strong>Login con</strong></label><br/>
								{{ ucfirst($instructor->provider) }}
							</div>
							<div class="col-md-3">
								<label><strong>ID red social</strong></label><br/>
								{{ $instructor->provider_id }}
							</div>
						</div>
					</div>
				</div>

				@if($instructor->isApproved())
				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Balance</h2>
						<h2 style="float: right; color: #5292e6 !important;">${{ $instructor->wallet->balance }}</h2>
					</div>
					<div class="list_general">
						
						<h6>Transacciones</h6>
						<table class="table">
							<thead>
								<tr>
									<th>ID</th>
									<th>Fecha</th>
									<th>Concepto</th>
									<th>Reserva</th>
									<th>Monto</th>
									<th>Saldo</th>
								</tr>
							</thead>
							<tbody>
								@foreach($walletMovements as $movement)
								<tr>
									<td>{{ $movement->id }}</td>
									<td>{{ $movement->date->format('d/m/Y') }}</td>
									<td>
										@if($movement->motive == App\InstructorWalletMovement::MOTIVE_RESERVATION_PAYMENT)
										Pago de reserva
										@elseif($movement->motive == App\InstructorWalletMovement::MOTIVE_COLLECTION)
										Retiro de dinero
										@endif
									</td>
									<td>
										@if($movement->motive == App\InstructorWalletMovement::MOTIVE_RESERVATION_PAYMENT)
										<a href="{{ route('admin.reservations.details', $movement->reservation->id) }}">#{{ $movement->reservation->code }}</a>
										@endif
									</td>
									<td>${{ round($movement->net_amount, 2) }}</td>
									<td>${{ round($movement->new_balance, 2) }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>

						
					</div>
					{{ $walletMovements->links() }}
				</div>
				@endif

				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Cuentas para retiro de dinero</h2>
					</div>
					<div class="list_general">
						
						<div class="row">

							<div class="col-md-6">
								<label>Cuenta bancaria:</label>
								@if($instructor->bankAccount)
								<div>
									<strong>CBU:</strong> {{ $instructor->bankAccount->cbu }}<br/>
									<strong>Titular:</strong> {{ $instructor->bankAccount->holder_name }}<br/>
									<strong>Documento:</strong> {{ $instructor->bankAccount->document_number }}<br/>
									<strong>CUIL/CUIT:</strong> {{ $instructor->bankAccount->cuil_cuit }}<br/>
								</div>
								@else
								No
								@endif
							</div>

							<div class="col-md-6">
								<label>Cuenta mercadopago:</label>
								@if($instructor->mpAccount)
								<br/>
								{{ $instructor->mpAccount->email }}
								@else
								No
								@endif
							</div>

						</div>
						
					</div>
				</div>



      		</div>

      		<div class="col-lg-6">
				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Detalles del servicio 
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
								<span class="badge badge-success" style="font-size: 14px">Activo</span>
								@else
								<span class="badge badge-secondary" style="font-size: 14px">Inactivo</span>
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


<script>
@if(!$errors->approval->isEmpty())
$('#approval-modal').modal("show");
@endif

@if(!$errors->doc_rejectal->isEmpty())
$('#reject-docs-modal').modal("show");
@endif
</script>


@endsection