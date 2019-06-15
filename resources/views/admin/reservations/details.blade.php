@extends('admin.layouts.main')


@section('custom-css')
<style type="text/css">
	.table-borderless td {
		border-top: 0;
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

@section('content')

	<!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item active">Lista de usuarios</li>
        <li class="breadcrumb-item active">Detalles de usuario</li>
      </ol>
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
									@if($reservation->lastPayment()->isProcessing())
									Procesando
									@elseif($reservation->lastPayment()->isFailed())
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

						@foreach($reservation->payments as $payment)

						@if(!$loop->first)
						<hr>
						@endif
						
						<div class="row" style="margin-bottom: 15px">

							<div class="col-md-3">
								<strong>ID pago</strong><br>
								{{ $payment->id }}
							</div>
							<div class="col-md-3">
								<strong>Estado</strong><br>
								<div style="font-size: 16px">
									@if($payment->isProcessing())
									<span class="badge badge-primary">Procesando</span>
									@elseif($payment->isSuccessful())
									<span class="badge badge-success">Exitoso</span>
									@elseif($payment->isFailed())
									<span class="badge badge-danger">Fallido</span>
									@elseif($payment->isRefunded())
									<span class="badge badge-info">Reembolsado</span>
									@elseif($payment->isChargebacked())
									<span class="badge badge-danger">Contracargado</span>
									@endif
								</div>
							</div>
							<div class="col-md-3">
								<strong>Medio de pago</strong><br>
								@if($payment->payment_method_code == App\Lib\PaymentMethods::CODE_MERCADOPAGO)
								MercadoPago (tarj. crédito)
								@endif
							</div>

							<div class="col-md-3">
								<strong>Total</strong><br>
								{{ round($payment->total_amount, 2).' '.$payment->currency_code }}
							</div>
						</div>

						@if($payment->isSuccessful())
						<div class="row">

							<div class="col-md-3">
								<strong>Fecha pagado</strong><br>
								{{ $payment->paid_at->format('d/m/Y H:i:s') }}
							</div>
							<div class="col-md-3">
								<strong>Tarifa proc. pago</strong><br>
								{{ round($payment->payment_provider_fee, 2).' '.$payment->currency_code }}
							</div>
							<div class="col-md-3">
								<strong>Monto neto</strong><br>
								{{ round($payment->net_amount, 2).' '.$payment->currency_code }}
							</div>

						</div>
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
										<td>${{ round($reservation->final_price - $reservation->payment_proc_fee, 2) }}</td>
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
								<tr style="font-size: 18px">
									<td>Total</td>
									<td>${{ round($reservation->final_price, 2) }}</td>
								</tr>
							</tbody>
						</table>
						<hr style="margin: 4px 0;">
						<label style="margin-top: 15px; font-size: 17px; color:#444;">Composición total</label>
						<table class="table table-sm table-borderless price-details-table">
							<tbody>
								<tr>
									<td>Tarifa servicio pagos</td>
									<td>${{ round($reservation->payment_proc_fee, 2) }}</td>
								</tr>
								<tr>
									<td>Comisión servicio ({{ round($reservation->service_fee / ($reservation->instructor_pay + $reservation->service_fee) * 100) }}% de precio clases)</td>
									<td>${{ round($reservation->service_fee, 2) }}</td>
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


