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
										<span style="color: #2e79b9">${{ $wallet->balance }} ARS</span>
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
										<button type="button" class="btn btn-success" @if($bankAccount) data-toggle="modal" data-target="#collection-modal" @else onclick="alert('Debes asociar tu cuenta bancaria para poder retirar tus fondos.')" @endif>Retirar fondos</button>
									</div>
									<div class="col-md-6" style="padding-top: 9px;">
										@if(!$bankAccount)
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

				@if($bankAccount && $wallet->collections()->pending()->count() > 0)
				<div class="card" style="margin: 15px 0">
					<div class="card-body">
						<h6 class="card-title">Extracciones de dinero pendientes</h6>
						<table class="table table-sm">

							<thead>
								<tr>
									<th>Fecha</th>
									<th>Monto</th>
									<th>Destino</th>
									<th>Estado</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($wallet->collections()->pending()->latest()->get() as $collection)
								<tr>
									<td>
										{{ $collection->created_at->format('d/m/Y') }}
									</td>
									<td>
										${{ $collection->amount }}
									</td>
									<td>Cuenta bancaria</td>
									<td>
										@if($collection->isPending())
										Pendiente
										@elseif($collection->isInProcess())
										Procesando
										@endif
									</td>
									<td>
										<form action="{{ url('instructor/panel/saldo/cancelar-retiro') }}" method="POST">
											@csrf
											<input type="hidden" name="collection_id" value="{{ $collection->id }}">
											<button type="button" class="btn btn-dark btn-sm" onclick="if(confirm('¿Cancelar esta extracción?')) $(this).parent().submit();"><i class="fas fa-times"></i></button>
										</form>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				@endif

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
								@if($walletMovements->count() == 0)
									<tr><td colspan="5" style="text-align: center;">No tienes movimientos</td></tr>
								@else
									@foreach($walletMovements as $movement)
									<tr>
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
											<a href="{{ route('instructor.reservation', $movement->reservation->code) }}">#{{ $movement->reservation->code }}</a>
											@endif
										</td>
										<td>${{ round($movement->net_amount, 2) }}</td>
										<td>${{ round($movement->new_balance, 2) }}</td>
									</tr>
									@endforeach
								@endif
							</tbody>
						</table>

						<div style="text-align: center;">{{ $walletMovements->links() }}</div>
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
@if($instructor->isApproved() && $bankAccount)
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

				@if($bankAccount->lockTimePassed())
				<form action="{{ url('instructor/panel/saldo/retirar') }}" method="POST" id="collection-form">
					@csrf
					<div class="form-group" style="text-align: center;">
						<label>Ingresa el monto en pesos</label>
						<input name="amount" type="text" class="form-control{{ $errors->collection->has('amount') ? ' is-invalid' : '' }}" value="{{ old('amount') }}" style="width: 150px;margin: 0 auto;">
						@if ($errors->collection->has('amount'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->collection->first('amount') }}</strong>
				        </span>
				    	@endif
					</div>
				</form>
				<div>
					<div style="margin-bottom: 12px">Los fondos se retirarán a la siguiente cuenta bancaria:</div>
					<div style="">
						<strong>CBU:</strong> {{ $bankAccount->cbu }}<br/>
						<strong>Titular:</strong> {{ $bankAccount->holder_name }}<br/>
						<strong>Documento:</strong> {{ $bankAccount->document_number }}<br/>
						<strong>CUIL/CUIT:</strong> {{ $bankAccount->cuil_cuit }}<br/>
					</div>
				</div>
				@else
				<div class="alert alert-info">
					Tu cuenta bancaria fue recientemente configurada, debés esperar al {{ $bankAccount->unlockTime()->format('d/m/Y H:i') }} para poder retirar dinero.
				</div>
				@endif
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				@if($bankAccount->lockTimePassed())
				<button type="button" class="btn btn-primary" onclick="if(confirm('¿Confirmar?')) $('#collection-form').submit();">Confirmar</button>
				@endif
			</div>
		</div>
	</div>
</div>
@endif
@endsection



@section('custom-js')

@if($instructor->isApproved() && !$errors->collection->isEmpty())
<script type="text/javascript">
	$('#collection-modal').modal("show");
</script>
@endif

@endsection