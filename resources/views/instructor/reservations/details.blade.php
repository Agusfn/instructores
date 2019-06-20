@extends('layouts.main')

@section('title', 'Reserva')


@section('custom-css')
<style type="text/css">

	.reservation-status {
		text-align: center;
	}

	.status-icon {
		font-size: 75px
	}


	.price-details-table tr > td:first-child {
		text-align: left;
	}
	.price-details-table tr > td:nth-child(2) {
		text-align: right;
	}
	.profile-pic {
		width: 120px;
		height: 120px;
		border-top-left-radius: 50% 50%;
		border-top-right-radius: 50% 50%;
		border-bottom-right-radius: 50% 50%;
		border-bottom-left-radius: 50% 50%;
	}
</style>
@endsection

@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-lg-9">

				<h4 class="add_bottom_30">Reserva #{{ $reservation->code }}</h4>

				@include('layouts.errors')

				<div class="row">

					<div class="col-md-7">
						
						<div class="card" style="margin-bottom: 30px; padding-top: 15px; padding-bottom: 15px">
							<div class="card-body">
							
								<div class="reservation-status">
									
									@if($reservation->isPaymentPending())
										@if($payment->isProcessing())
										<div>
											<i class="far fa-clock status-icon"></i><br/>
											Procesando pago<br/>
										</div>
										@elseif($payment->isFailed())
										<div>
											<i class="far fa-clock status-icon"></i><br/>
											Pago pendiente<br/>
										</div>
										@endif
									@elseif($reservation->isPendingConfirmation())
									<div style="color: #3d9630; margin-bottom: 20px">
										<i class="far fa-check-circle status-icon"></i><br/>
										Pago realizado
									</div>
									Pendiente de confirmación<br/>
									<div class="btn-group" role="group">
										<button type="button" class="btn btn-default" style="color: #36b11d;" data-tooltip="tooltip" data-placement="top" title="Confirmar reserva" data-toggle="modal" data-target="#confirm-reservation-modal">
											<i class="fas fa-check"></i>
										</button>
										<button type="button" class="btn btn-default" style="color: #bc2f2f;" data-tooltip="tooltip" data-placement="top" title="Rechazar reserva y hacer un reembolso" data-toggle="modal" data-target="#reject-reservation-modal">
											<i class="fas fa-times"></i>
										</button>
									</div>
									@elseif($reservation->isConfirmed())
									<div style="color: #3d9630">
										<i class="far fa-check-circle status-icon"></i><br/>
										Reserva confirmada
									</div>
									@elseif($reservation->isRejected())
									<div style="color: #bc2f2f">
										<i class="far fa-times-circle status-icon"></i><br/>
										Reserva rechazada por el instructor
									</div>
									@elseif($reservation->isFailed())
									<div style="color: #bc2f2f">
										<i class="far fa-times-circle status-icon"></i><br/>
										Pago fallido
									</div>
									@elseif($reservation->isCanceled())
									<div style="color: #bc2f2f">
										<i class="far fa-times-circle status-icon"></i><br/>
										Cancelada
									</div>
									@endif


								</div>

							</div>
						</div>


						@if(!$payment->isFailed())						
						<div class="card" style="margin-bottom: 30px">
							<div class="card-body">
								<h6 class="card-title">Detalles del pago</h6>

								<div class="row">
									<div class="col-md-6">
										<label>Estado:</label><br/>
										@if($payment->isProcessing())
										<span class="badge badge-primary">Procesando</span>
										@elseif($payment->isSuccessful())
										<span class="badge badge-success">Exitoso</span>
										@elseif($payment->isRefunded())
										<span class="badge badge-secondary">Reembolsado</span>
										@elseif($payment->isChargebacked())
										<span class="badge badge-danger">Con contracargo</span>
										@endif
									</div>
									<div class="col-md-6">
										<label>Medio de pago:</label><br/>
										@if($payment->isMercadoPago())
										Tarj. de crédito - {{ ucfirst($payment->mercadopagoPayment->payment_method_id) }}
										@endif
									</div>							
								</div>

								<div class="row" style="margin-top: 15px">
									@if(!$payment->isFailed() && !$payment->isProcessing())
									<div class="col-md-6">
										<label>Fecha pagado:</label><br/>
										{{ $payment->paid_at->format('d/m/Y H:i') }}
									</div>
									@endif
									@if($payment->isMercadoPago())
									<div class="col-md-6">
										<label>Cuotas:</label><br/>
										{{ $payment->mercadopagoPayment->installment_amount }}
									</div>	
									@endif
								</div>

							</div>
						</div>
						@endif



						<div class="card">
							<div class="card-body">
								<h6 class="card-title">
									Composición del precio 
									<div style="float: right;">
										<a data-toggle="collapse" href="#collapse-price-breakdown" role="button">
											<i class="far fa-plus-square"></i>
										</a>
									</div>
								</h6>

								<div class="collapse" id="collapse-price-breakdown">
									${{ round($reservation->price_per_block, 2) }}/2hs x {{ $reservation->time_blocks_amount }} = ${{ round($reservation->price_per_block * $reservation->time_blocks_amount, 2) }} (precio base p/ pers.)

									<table class="table table-sm" style="margin-top: 10px">
										<thead>
											<tr>
												<th>Persona</th>
												<th>Precio base</th>
												<th>Dto.</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>
											@foreach($reservation->priceBreakdown() as $person)
											<tr>
												<td>{{ $loop->iteration }}º</td>
												<td>${{ $person[0] }}</td>
												<td>-${{ $person[1] }}</td>
												<td>${{ $person[2] }}</td>
											</tr>
											@endforeach
											<tr>
												<td></td>
												<td></td>
												<td></td>
												<td>${{ round($reservation->instructor_pay + $reservation->service_fee, 2) }}</td>
											</tr>
										</tbody>
									</table>
								</div>

							</div>
						</div>

					</div>

					<div class="col-md-5">
						<div class="card">
							<div class="card-body">
								<h4>Detalles de la reserva</h4>
								<hr>
								<div>
									<strong>Cliente</strong>
									<div style="text-align: center;">
										<img class="profile-pic" src="{{ $reservation->user->getProfilePicUrl() }}"><br/>
										@if($reservation->isConfirmed())
											{{ $reservation->user->name.' '.$reservation->user->surname }}<br/>
											{{ $reservation->user->email }}
											@if($reservation->user->phone_number)
											<br/>{{ $reservation->user->phone_number }}
											@endif
										@else
											{{ $reservation->user->name.' '.$reservation->user->surname[0].'.' }}
										@endif
									</div>
								</div>						

								<hr>
								<div class="add_bottom_15">
									<strong>Disciplina</strong><br/>
									{{ ucfirst($reservation->sport_discipline) }}
								</div>
								<div class="add_bottom_15">
									<strong>Fecha y hora</strong><br/>
									{{ $reservation->reserved_class_date->format('d/m/Y') }}<br/>
									{{ $reservation->reserved_time_start.':00 - '.$reservation->reserved_time_end.':00 hs' }}
								</div>
								<div>
									<strong>Personas a dar clase</strong><br/>
									@if($reservation->adults_amount > 0)
									{{ $reservation->adults_amount }} adultos<br/>
									@endif
									@if($reservation->kids_amount > 0)
									{{ $reservation->kids_amount }} niños
									@endif
								</div>
								<hr>
								<table class="table table-sm table-borderless price-details-table">
									<tbody>
										<tr>
											<td>Precio clases</td>
											<td>${{ round($reservation->instructor_pay + $reservation->service_fee, 2) }}</td>
										</tr>
										<tr>
											<td>Tarifa servicio pagos</td>
											<td>${{ round($reservation->payment_proc_fee, 2) }}</td>
										</tr>
										@if($reservation->mp_financing_cost > 0)
										<tr>
											<td>Financiación MercadoPago</td>
											<td>${{ round($reservation->mp_financing_cost, 2) }}</td>
										</tr>
										@endif
										<tr style="font-size: 18px">
											<td>Total</td>
											<td>${{ round($reservation->final_price, 2) }}</td>
										</tr>
									</tbody>
								</table>
								<hr style="margin: 4px 0;">
								<table class="table table-sm table-borderless price-details-table">
									<tbody>
										<tr>
											<td>Precio clases</td>
											<td>${{ round($reservation->instructor_pay + $reservation->service_fee, 2) }}</td>
										</tr>
										<tr>
											<td>Comisión servicio ({{ round($reservation->service_fee / ($reservation->instructor_pay + $reservation->service_fee) * 100) }}%)</td>
											<td>-${{ round($reservation->service_fee, 2) }}</td>
										</tr>
										<tr style="font-size: 17px">
											<td>Saldo neto</td>
											<td>${{ round($reservation->instructor_pay, 2) }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>

				</div>
				

			</div>

		</div>


	</div>
            
@endsection


@section('body-end')
@if($reservation->isPendingConfirmation())
<div class="modal menu_fixed" style="z-index: 1050" tabindex="-1" role="dialog" id="confirm-reservation-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Confirmar reserva</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ url('instructor/panel/reservas/'.$reservation->code.'/confirmar') }}" method="POST" id="confirm-form">
					@csrf
					<div class="form-group">
						<label>(Opcional) Ingresa un mensaje que quieras dejarle al cliente </label>
						<textarea class="form-control" name="confirm_message"></textarea>
					</div>
				</form>
				Luego de confirmar, podrás ver los datos de contacto del cliente.
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="if(confirm('¿Desea confirmar la reserva?')) $('#confirm-form').submit();">Confirmar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal menu_fixed" style="z-index: 1050" tabindex="-1" role="dialog" id="reject-reservation-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Rechazar reserva</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{ url('instructor/panel/reservas/'.$reservation->code.'/rechazar') }}" method="POST" id="reject-form">
					@csrf
					<div class="form-group">
						<label>Ingresa el motivo (opcional)</label>
						<textarea class="form-control" name="reject_reason"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="if(confirm('¿Desea rechazar la reserva?')) $('#reject-form').submit();">Rechazar</button>
			</div>
		</div>
	</div>
</div>
@endif
@endsection


@section('custom-js')
<script type="text/javascript">
	$(function () {
	  $('[data-tooltip="tooltip"]').tooltip()
	})
</script>
@endsection