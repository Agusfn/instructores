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

	
			@include('user.panel-sidebar')

			<div class="col-lg-9">

				<h4 class="add_bottom_30">Reserva #{{ $reservation->code }}

					@if($reservation->claim)
					<a href="{{ route('user.reservations.claim', $reservation->code) }}" class="btn btn-light" style="float: right;">Ver reclamo</a>
					@elseif($reservation->isPaymentPending() || $reservation->isPendingConfirmation() || $reservation->isConfirmed())
					<a href="{{ route('user.reservations.make-claim', $reservation->code) }}" class="btn btn-light" style="float: right;">Tengo un problema</a>
					@endif

				</h4>

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
										@elseif($payment->isPending() && $payment->isMercadoPago())
										<div>
											<i class="far fa-clock status-icon"></i><br/>
											Pago pendiente<br/>
											<a href="{{ $payment->mercadopagoPayment->ext_resource_url }}" target="_blank">Realiza el pago</a> del dentro de las siguientes {{ \App\Reservation::RETRY_PAYMENT_TIME_HS }} horas.
										</div>
										@elseif($payment->isFailed())
										<div>
											No se pudo realizar el pago.<br>
											<a href="{{ route('reservation.retry-payment', $reservation->code) }}">Reintentalo</a> dentro de las sgtes {{ \App\Reservation::RETRY_PAYMENT_TIME_HS }} hs.
										</div>
										@endif
									@elseif($reservation->isPendingConfirmation())
									<div style="color: #3d9630; margin-bottom: 20px">
										<i class="far fa-check-circle status-icon"></i><br/>
										Pago realizado
									</div>
									Pendiente de confirmación del instructor
									@elseif($reservation->isConfirmed())
									<div style="color: #3d9630">
										<i class="far fa-check-circle status-icon"></i><br/>
										Reserva confirmada
									</div>
									@elseif($reservation->isConcluded())
									<div style="color: #3d9630">
										<i class="far fa-check-circle status-icon"></i><br/>
										Reserva concluida
									</div>
									@elseif($reservation->isRejected())
									<div style="color: #bc2f2f">
										<i class="far fa-times-circle status-icon"></i><br/>
										Reserva rechazada por el instructor<br/>
										El pago fue reembolsado
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

						@if($reservation->claim)
						asd
						@endif


						@if($reservation->isConfirmed() && $reservation->confirm_message)
						<div class="card" style="margin-bottom: 30px">
							<div class="card-body">
								<h6 class="card-title">Mensaje de confirmación del instructor</h6>
								<span style="font-style: italic;">&quot;{{ $reservation->confirm_message }}&quot;</span>
							</div>
						</div>
						@endif

						@if(!$payment->isFailed())						
						<div class="card" style="margin-bottom: 30px">
							<div class="card-body">
								<h6 class="card-title">Detalles del pago</h6>

								<div class="row">
									<div class="col-md-6">
										<label>Estado:</label><br/>
										@if($payment->isPending())
										<span class="badge badge-secondary">Pendiente de pago</span>
										@elseif($payment->isProcessing())
										<span class="badge badge-primary">Procesando</span>
										@elseif($payment->isSuccessful())
										<span class="badge badge-success">Exitoso</span>
										@elseif($payment->isCanceled())
										<span class="badge badge-secondary">Expirado/cancelado</span>
										@elseif($payment->isRefunded())
										<span class="badge badge-secondary">Reembolsado</span>
										@elseif($payment->isChargebacked())
										<span class="badge badge-danger">Con contracargo</span>
										@endif
									</div>
									<div class="col-md-6">
										<label>Medio de pago:</label><br/>
										@if($payment->isMercadoPago())

											@if($payment->mercadopagoPayment->isWithCreditCard())
												Tarj. de crédito - {{ ucfirst($payment->mercadopagoPayment->payment_method_id).' ...'.$payment->mercadopagoPayment->last_four_digits }}
											@else
												{{ ucfirst($payment->mercadopagoPayment->payment_method_id) }}
											@endif

										@endif
									</div>								
								</div>

								<div class="row" style="margin-top: 15px">
									@if($payment->isSuccessful() || $payment->isRefunded() || $payment->isChargebacked())
									<div class="col-md-6">
										<label>Fecha pagado:</label><br/>
										{{ $payment->paid_at->format('d/m/Y H:i') }}
									</div>
									@endif
									@if($payment->isMercadoPago() && $payment->mercadopagoPayment->isWithCreditCard())
									<div class="col-md-6">
										<label>Cuotas:</label><br/>
										{{ $payment->mercadopagoPayment->installment_amount }}
									</div>	
									@endif
								</div>

							</div>
						</div>
						@endif

					</div>

					<div class="col-md-5">
						<div class="card">
							<div class="card-body">
								<h4>Detalles de la reserva</h4>
								<hr>
								<div>
									<strong>Instructor</strong>
									<div style="text-align: center;">
										<img class="profile-pic" src="{{ $reservation->instructor->getProfilePicUrl() }}"><br/>
										@if($reservation->isConfirmed() || $reservation->isConcluded())
											{{ $reservation->instructor->name.' '.$reservation->instructor->surname }}<br/>
											{{ $reservation->instructor->email }}
											@if($reservation->instructor->phone_number)
											<br/>{{ $reservation->instructor->phone_number }}
											@endif
										@else
											{{ $reservation->instructor->name.' '.$reservation->instructor->surname[0].'.' }}
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
									<strong>Personas</strong><br/>
									@if($reservation->adults_amount > 0)
									{{ $reservation->adults_amount }} adultos<br/>
									@endif
									@if($reservation->kids_amount > 0)
									{{ $reservation->kids_amount }} niños
									@endif
								</div>
								<hr>

								@if($payment->isProcessing() && $payment->isMercadoPago() && $payment->mercadopagoPayment->installment_amount > 1)
								<div class="alert alert-info">
									La información del precio total con el costo financiero se actualizará una vez acreditado el pago.
								</div>
								@endif
								
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
								
							</div>
						</div>
					</div>

				</div>
				

			</div>

		</div>


	</div>
            
@endsection



@section('custom-js')
<script type="text/javascript">
	$(function () {
	  $('[data-tooltip="tooltip"]').tooltip()
	})
</script>
@endsection