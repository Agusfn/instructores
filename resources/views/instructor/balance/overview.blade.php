@extends('layouts.main')


@section('title', 'Mi saldo')



@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-lg-9">

				@if($instructor->isApproved())

				<h4 class="add_bottom_30">Mi cuenta de saldo</h4>

				<div class="row">
					<div class="col-sm-6">
						<div class="card">
							<div class="card-body" style="padding: 24px 20px;">
								<div class="row" style="font-size: 19px">
									<div class="col-md-6">
										Balance
									</div>

									<div class="col-md-6">
										<span style="color: #2e79b9">$14610.50 ARS</span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<button type="button" class="btn btn-success" data-toggle="modal" data-target="#collection-modal">Retirar fondos</button>
									</div>
									<div class="col-md-6" style="padding-top: 9px;">
										@if($instructor->bankAccount == null)
										<a href="{{ route('instructor.balance.bank-account') }}">Agregar cta. bancaria</a>
										@else
										<a href="{{ route('instructor.balance.bank-account') }}">Modificar cta. bancaria</a>
										@endif
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card">
					<div class="card-body">
						<h5 class="card-title">Movimientos</h5>
						<table class="table">

							<thead>
								<tr>
									<th>Fecha</th>
									<th>Concepto</th>
									<th>Reserva</th>
									<th>Monto</th>
									<th>Saldo</th>
								</tr>
							</thead>
							<tbody>
								@foreach($balanceMovements as $movement)
								<tr>
									<td>{{ date('d/m/Y', strtotime($movement->date)) }}</td>
									<td>
										@if($movement->motive == App\InstructorBalanceMovement::MOTIVE_RESERVATION_PAYMENT)
										Pago de reserva
										@elseif($movement->motive == App\InstructorBalanceMovement::MOTIVE_COLLECTION)
										Retiro de dinero
										@endif
									</td>
									<td>
										@if($movement->motive == App\InstructorBalanceMovement::MOTIVE_RESERVATION_PAYMENT)
										<a href="">{{ $movement->reservation->code }}</a>
										@endif
									</td>
									<td>${{ floatval($movement->net_ammount) }}</td>
									<td></td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				@else
				<div class="alert alert-warning">
					Tu cuenta no ha sido aprobada aún. Para empezar a ofrecer tus servicios debés verificar tu documentación de identidad y certificación.
				</div>
				@endif


			</div>

		</div>


	</div>
            
@endsection




@section('body-end')
<div class="modal menu_fixed" style="z-index: 1050" tabindex="-1" role="dialog" id="collection-modal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Retirar fondos</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="form-group" style="text-align: center;">
					<label>Ingresa el monto en Pesos</label>
					<input type="text" class="form-control" style="width: 150px;margin: 0 auto;">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="$('#approve-form').submit();">Confirmar</button>
			</div>
		</div>
	</div>
</div>
@endsection