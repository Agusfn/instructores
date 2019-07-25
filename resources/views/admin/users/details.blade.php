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

@section('content')

	<!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Panel</a></li>
        <li class="breadcrumb-item active">Lista de usuarios</li>
        <li class="breadcrumb-item active">Detalles de usuario</li>
      </ol>

		<div class="box_general padding_bottom">
			
			<form action="{{ url('admin/usuarios/'.$user->id.'/suspender') }}" method="POST" style="display: inline;margin-right: 30px">
				@csrf
				@if(!$user->suspended)
				<button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('¿Suspender cuenta?')) $(this).parent().submit();">Suspender cuenta</button>
				@else
				<button type="button" class="btn btn-info btn-sm" onclick="if(confirm('¿Reahabilitar cuenta?')) $(this).parent().submit();">Habilitar cuenta</button>
				@endif
			</form>

			@if($user->reservations()->count() == 0)
			<form action="{{ url('admin/usuarios/'.$user->id.'/eliminar') }}" method="POST" style="display: inline;">
				@csrf
				<button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('¿ELIMINAR cuenta? No se podrá recuperar')) $(this).parent().submit();">Eliminar cuenta</button>
			</form>
			@endif


		</div>


      	<div class="row">

      		<div class="col-lg-6">

				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Datos personales</h2>
					</div>
					<div class="list_general">

						<div class="row" style="margin: 20px 0 40px 0">
							<div class="col-md-3">
								@if($user->profile_picture)
									<img src="{{ $user->getProfilePicUrl() }}" class="profile-pic">
								@else
									<img src="{{ asset('resources/admin/img/avatar.jpg') }}" class="profile-pic">
								@endif
							</div>

							<div class="col-md-9">
								<div class="row" style="margin-bottom: 20px">
									<div class="col-md-6">
										<label><strong>Nombre y apellido</strong></label><br/>
										{{ $user->name.' '.$user->surname }}
									</div>
									<div class="col-md-6">
										<label><strong>E-mail</strong></label><br/>
										{{ $user->email }}
										@if(!$user->hasSocialLogin() && !$user->hasVerifiedEmail())
										<span class="badge badge-warning">Pend. verif</span>
										@endif
									</div>
								</div>

								<div class="row" style="margin-bottom: 20px">
									<div class="col-md-6">
										<label><strong>Nro tel</strong></label><br/>
										@if($user->phone_number)
											{{ $user->phone_number }}
										@else
											-
										@endif
									</div>
								</div>

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
								<label><strong>ID usuario</strong></label><br/>
								{{ $user->id }}
							</div>
							<div class="col-md-3">
								<label><strong>Registrado el</strong></label><br/>
								@if($user->created_at)
								{{ $user->created_at->format('d/m/Y H:i:s') }}
								@else
								-
								@endif
							</div>
							<div class="col-md-3">
								<label><strong>Login con</strong></label><br/>
								@if($user->hasSocialLogin())
								{{ ucfirst($user->provider) }}
								@else
								Login normal
								@endif
							</div>
							<div class="col-md-3">
								<label><strong>ID red social</strong></label><br/>
								@if($user->hasSocialLogin())
								{{ $user->provider_id }}
								@else
								-
								@endif
							</div>
						</div>
					</div>
				</div>


      		</div>

      		<div class="col-lg-6">
				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Reservas</h2>
					</div>
					<div class="list_general">
						
						@if($reservations->count() > 0)
						<table class="table table-sm">
							<thead>
								<tr>
									<th></th>
									<th>Cod.</th>
									<th>Estado</th>
									<th>Instructor</th>
									<th>Fecha</th>
									<th>Horas</th>
									<th>Pers.</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								
								@foreach($reservations as $reservation)

								<tr>
									<td><a href="{{ route('admin.reservations.details', $reservation->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-search" aria-hidden="true"></i></a></td>
									<td>{{ $reservation->code }}</td>
									<td>
										@if($reservation->isPaymentPending())
										<span class="badge badge-secondary">Pago pend.</span>
										@elseif($reservation->isPendingConfirmation())
										<span class="badge badge-primary">Pend. confirmación</span>
										@elseif($reservation->isFailed())
										<span class="badge badge-danger">Pago fallido</span>
										@elseif($reservation->isRejected())
										<span class="badge badge-danger">Rechazada</span>
										@elseif($reservation->isConfirmed())
										<span class="badge badge-success">Confirmada</span>
										@elseif($reservation->isConcluded())
										<span class="badge badge-success">Concluída</span>
										@elseif($reservation->isCanceled())
										<span class="badge badge-danger">Cancelada</span>
										@endif
									</td>
									<td>
										<a href="{{ route('admin.instructors.details', $reservation->instructor->id) }}">
										{{ $reservation->instructor->name.' '.$reservation->instructor->surname[0].'.' }}
										</a>
									</td>
									<td>{{ $reservation->reserved_class_date->format('d/m/Y') }}</td>
									<td>{{ $reservation->readableHourRange(true) }}</td>
									<td>{{ $reservation->personAmount() }}</td>
									<td>${{ round($reservation->final_price, 2) }}</td>
								</tr>

								@endforeach

							</tbody>
						</table>
						@else
						<div style="text-align: center;margin-bottom: 15px">El usuario no posee reservas realizadas.</div>
						@endif
					</div>
				</div>
      		</div>

		</div>
		<!-- /box_general-->

@endsection


