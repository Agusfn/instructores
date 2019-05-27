@extends('layouts.main')






@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-9">

				<h4 class="add_bottom_30">
					Saldo
					<div style="float:right; color: #2e79b9">$14610.50</div>
				</h4>

				<div style="margin-bottom: 20px">
					<button type="button" class="btn btn-success" data-toggle="modal" data-target="#collection-modal">Retirar fondos</button>
				</div>


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