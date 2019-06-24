@extends('admin.layouts.main')


@section('custom-css')
<style type="text/css">
	.table-borderless td, .table-borderless th {
		border: 0 !important;
	}

	.profile-pic {
		width: 120px;
		height: 120px;
		border-top-left-radius: 50% 50%;
		border-top-right-radius: 50% 50%;
		border-bottom-right-radius: 50% 50%;
		border-bottom-left-radius: 50% 50%;
	}
	.price-details-table tr > td:first-child {
		text-align: left;
	}
	.price-details-table tr > td:nth-child(2) {
		text-align: right;
	}
</style>
@endsection


@section('body-start')


@if($reservation->isPaymentPending() || $reservation->isPendingConfirmation() || $reservation->isConfirmed())
<div class="modal" tabindex="-1" role="dialog" id="cancel-reservation-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Cancelar reserva</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="alert alert-info">
					Se enviará un e-mail al instructor y al usuario, y se reembolsará el pago si este fue realizado.
				</div>
				<form action="{{ url('admin/reservas/'.$reservation->id.'/cancelar') }}" method="POST" id="cancel-reservation-form">
					@csrf
					<div class="form-group">
						<label>Ingrese un motivo (Opcional)</label>
						<textarea class="form-control" name="cancel_reason"></textarea>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="if(confirm('¿Seguro?')) $('#cancel-reservation-form').submit();">Confirmar</button>
			</div>
		</div>
	</div>
</div>
@endif



@endsection



@section('content')

		<!-- Breadcrumbs-->
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#">Dashboard</a></li>
			<li class="breadcrumb-item active">Lista de usuarios</li>
			<li class="breadcrumb-item active">Detalles de usuario</li>
		</ol>


		@include('layouts.errors')

		<div class="row">
			<div class="col-md-10">
				<div class="box_general padding_bottom">
					<div class="list_general">
						
						@if($reservation->isPaymentPending() || $reservation->isPendingConfirmation() || $reservation->isConfirmed())
							@if($reservation->isPaymentPending())
							<button class="btn btn-danger" data-toggle="modal" data-target="#cancel-reservation-modal">Cancelar</button>
							@elseif($reservation->isPendingConfirmation() || $reservation->isConfirmed())
							<button class="btn btn-danger" data-toggle="modal" data-target="#cancel-reservation-modal">Cancelar y reembolsar</button>
							@endif
						@endif


					</div>
				</div>
			</div>
		</div>

      	<div class="row">

      		<div class="col-md-6">

				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Reserva #{{ $reservation->code }}</h2>
					</div>
					<div class="list_general">

						<div class="row" style="margin-bottom: 15px">
							<div class="col-md-4">
								<strong>Id</strong><br>
								{{ $reservation->id }}
							</div>
							<div class="col-md-4">
								<strong>Código</strong><br>
								{{ $reservation->code }}
							</div>
							<div class="col-md-4">
								<strong>Estado</strong><br>
								<div style="font-size: 16px"> 
								@if($reservation->isPaymentPending())
									<span class="badge badge-secondary">Pago pendiente - 
									@if($reservation->lastPayment->isPending()) 
									Efectivo
									@elseif($reservation->lastPayment->isProcessing())
									Procesando
									@elseif($reservation->lastPayment->isFailed())
									Reintentar
									@endif
									</span>
								@elseif($reservation->isPendingConfirmation())
									<span class="badge badge-primary">Pend. confirmación instructor</span>
								@elseif($reservation->isFailed())
									<span class="badge badge-danger">Pago fallido</span>
								@elseif($reservation->isRejected())
									<span class="badge badge-danger">Rechazada por instructor</span>
								@elseif($reservation->isConfirmed())
									<span class="badge badge-success">Confirmada</span>
								@elseif($reservation->isConcluded())
									<span class="badge badge-danger">Concluída</span>
								@elseif($reservation->isCanceled())
									<span class="badge badge-danger">Cancelada</span>
								@endif
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<strong>Fecha creada</strong><br>
								{{ $reservation->created_at->format('d/m/Y H:i:s') }}
							</div>
							<!--div class="col-md-4">
								<strong>Fecha ultima actualiz.</strong><br>
								{{ $reservation->updated_at->format('d/m/Y H:i:s') }}
							</div-->
							@if($reservation->isConfirmed() && $reservation->confirm_message)
							<div class="col-md-8">
								<strong>Mensaje de confirmación</strong><br>
								<textarea class="form-control" readonly="">{{ $reservation->confirm_message }}</textarea>
							</div>
							@elseif($reservation->isRejected() && $reservation->reject_message)
							<div class="col-md-8">
								<strong>Mensaje de rechazo</strong><br>
								<textarea class="form-control" readonly="">{{ $reservation->reject_message }}</textarea>
							</div>
							@endif

						</div>
					</div>
				</div>

				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Pagos</h2>
					</div>
					<div class="list_general">

						@foreach($reservation->payments()->newestsFirst()->get() as $payment)

							@if(!$loop->first)
							<hr>
							@endif
							

							<table class="table table-borderless table-sm">
								<thead>
									<tr>
										<th>ID</th>
										<th>Fecha creado</th>
										<th>Estado</th>
										<th>Medio de pago</th>
										<th>Cuotas</th>
										<th>Total</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>{{ $payment->id }}</td>
										<td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
										<td>
											<div style="font-size: 16px">
												@if($payment->isProcessing())
												<span class="badge badge-primary">Procesando</span>
												@elseif($payment->isPending())
												<span class="badge badge-secondary">Pendiente</span>
												@elseif($payment->isSuccessful())
												<span class="badge badge-success">Exitoso</span>
												@elseif($payment->isCanceled())
												<span class="badge badge-danger">Expirado/cancelado</span>
												@elseif($payment->isFailed())
												<span class="badge badge-danger">Fallido</span>
												@elseif($payment->isRefunded())
												<span class="badge badge-info">Reembolsado</span>
												@elseif($payment->isChargebacked())
												<span class="badge badge-danger">Contracargado</span>
												@endif
											</div>
										</td>
										<td>
											@if($payment->isMercadoPago())

												@if($payment->mercadopagoPayment->isWithCreditCard())
													@if($payment->mercadopagoPayment->payment_method_id != null)
														{{ ucfirst($payment->mercadopagoPayment->payment_method_id).' ...'.$payment->mercadopagoPayment->last_four_digits }} (MP)
													@else
														MercadoPago (T.C.)
													@endif
												@else
													{{ ucfirst($payment->mercadopagoPayment->payment_method_id) }}
												@endif

											@endif
										</td>
										<td>
											@if($payment->isMercadoPago())
												@if($payment->mercadopagoPayment->installment_amount != null)
													{{ $payment->mercadopagoPayment->installment_amount }}
												@else
													-
												@endif
											@endif
										</td>
										<td>{{ round($payment->total_amount, 2).' '.$payment->currency_code }}</td>
									</tr>
								</tbody>
							</table>
							
							

							@if($payment->isSuccessful())
							
							<table class="table table-borderless table-sm">
								<thead>
									<tr>
										<th>Fecha pago</th>
										<th>Tarifa proc.</th>
										<th>Interés finan.</th>
										<th>Recibido neto</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>{{ $payment->paid_at->format('d/m/Y H:i') }}</td>
										<td>{{ round($payment->payment_provider_fee, 2).' '.$payment->currency_code }}</td>
										<td>{{ round($payment->financing_costs, 2).' '.$payment->currency_code }}</td>
										<td>{{ round($payment->net_received, 2).' '.$payment->currency_code }}</td>
									</tr>
								</tbody>
							</table>

							@endif

						@endforeach

					</div>
				</div>

				<div class="box_general padding_bottom">
					<div class="header_box">
						<h6 class="d-inline-block">Composición del precio de clase</h6>
						<div style="float: right;">
							<a data-toggle="collapse" href="#collapse-price-breakdown" role="button">
								<i class="fa fa-plus-square-o" aria-hidden="true"></i>
							</a>
						</div>
					</h6>
					</div>
					<div class="list_general">

						<div class="collapse" id="collapse-price-breakdown">
							<strong>Precio bloque 2hs:</strong> ${{ round($reservation->price_per_block, 2) }}<br/>
							${{ round($reservation->price_per_block, 2) }} x bloques {{ $reservation->time_blocks_amount }} = ${{ round($reservation->price_per_block * $reservation->time_blocks_amount, 2) }} (precio base p/ pers.)

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


      		<div class="col-md-4">
				<div class="box_general padding_bottom">
					<div class="header_box">
						<h2 class="d-inline-block">Detalles de las clases</h2>
					</div>
					<div class="list_general">
						
						<div class="row">
							<div class="col-lg-6">
								<strong>Cliente</strong>
								<div style="text-align: center;">
									<a href="{{ route('admin.users.details', $reservation->user->id) }}">
										<img class="profile-pic" src="{{ $reservation->user->getProfilePicUrl() }}"><br/>
									</a>
									<a href="{{ route('admin.users.details', $reservation->user->id) }}">{{ $reservation->user->name.' '.$reservation->user->surname }}<br/></a>
								</div>
							</div>
							<div class="col-lg-6">
								<strong>Instructor</strong>
								<div style="text-align: center;">
									<a href="{{ route('admin.instructors.details', $reservation->instructor->id) }}">
										<img class="profile-pic" src="{{ $reservation->instructor->getProfilePicUrl() }}"><br/>
									</a>
									<a href="{{ route('admin.instructors.details', $reservation->instructor->id) }}">{{ $reservation->instructor->name.' '.$reservation->instructor->surname }}<br/></a>
								</div>
							</div>
						</div>						

						<hr>

						<div class="row">
							<div class="col-lg-6">
								<div class="add_bottom_15">
									<strong>Disciplina</strong><br/>
									{{ ucfirst($reservation->sport_discipline) }}
								</div>
								<div class="add_bottom_15">
									<strong>Fecha y hora</strong><br/>
									{{ $reservation->reserved_class_date->format('d/m/Y') }}<br/>
									{{ $reservation->reserved_time_start.':00 - '.$reservation->reserved_time_end.':00 hs' }}
								</div>
							</div>
							<div class="col-lg-6">
								<strong>Personas a dar clase</strong><br/>
								@if($reservation->adults_amount > 0)
								{{ $reservation->adults_amount }} adultos<br/>
								@endif
								@if($reservation->kids_amount > 0)
								{{ $reservation->kids_amount }} niños
								@endif
							</div>
						</div>


						<hr>

						@if($reservation->lastPayment->isProcessing() && $reservation->lastPayment->isMercadoPago() && $reservation->lastPayment->mercadopagoPayment->installment_amount > 0)
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
						<hr style="margin: 4px 0;">
						<label style="margin-top: 15px; font-size: 17px; color:#444;">Pago a instructor</label>
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
								<tr>
									<td>Pago instructor</td>
									<td>${{ round($reservation->instructor_pay, 2) }}</td>
								</tr>


							</tbody>
						</table>

					</div>
				</div>
      		</div>

		</div>
		<!-- /box_general-->

@endsection


