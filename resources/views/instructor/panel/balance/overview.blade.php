@extends('instructor.panel.layouts.main-layout')


@section('title', 'Mi saldo')

@section('custom-css')
<style>
	.noUi-connect {
		background: #2489c5;
	}
	.noUi-pips-horizontal {
		max-height: 53px;
	}
	.dz-image img {
		width: 100%;
		height: 100%
	}
	.dropzone .dz-preview:hover .dz-image img {
	  -webkit-filter: blur(0px);
	  filter: blur(0px); 
	}
	.dz-details {
		display: none;
	}


 .sr {background-color: whitesmoke;}.
    #page {background-color: whitesmoke;}
    .mm-slideout { 
        background-color: #299aea!important;
        color: white !important;

    }
    .margin_80_55 {
        background-color: whitesmoke !important;

    }
    
    #registbotton{
        margin-top: 0%;
        margin-bottom: 0%;
        
       
    }

    #ofertas {
        display: none;
    }

    .main_title_3 span em {
    width: 60px;
    height: 2px;
    background-color: #0054a6!important;
    display: block;
}
    .mm-slideout {
        border-bottom: 1px solid #ededed!important;
   
    color: black !important;
}
   .mm-slideout p{
    
    color: black !important;
}
 .mm-slideout   ul > li span > a {
    color: white !important;   
}

.mm-slideout   ul > li span > a:hover {
    color: #fc5b62 !important;   
}

.hamburger-inner, .hamburger-inner::after, .hamburger-inner::before {
    width: 30px;
    height: 4px;
    background-color: white !important;
    border-radius: 0;
    position: absolute;
    transition-property: transform;
    transition-duration: .15s;
    transition-timing-function: ease;
}



.movements-table  .row.head > div {
	font-weight: bold
}

.movements-table .row {
	padding: 13px 0;
	margin: 0;
	border-bottom: 1px solid #DDD;
}

.movements-table > .row {
	color: #505050;
}

.movements-table > .row:not(:first-child):hover {
	background-color: #F0F0F0;
}

.movements-table .row > div {
	padding-top: 3px;
	padding-bottom: 3px;
}


.space {
	width: 20px; 
	height: 1px
}


	</style>
@endsection


@section('panel-tab-content')



				@if($instructor->isApproved())

				<h4 class="add_bottom_30">Mi cuenta de saldo</h4>

				@include('layouts.errors')

				<div class="row">
					<div class="col-md-6" style="padding-right: 5px">
						<div class="card" style="height: calc(100% - 10px);">
							<div class="card-body" style="padding: 24px 20px;">

								<div class="row" style="font-size: 18px">
									<div class="col-7">
										Balance
									</div>
									<div class="col-5">
										<span style="color: #2e79b9">${{ $wallet->balance }} <small>ARS</small></span>
									</div>
								</div>

								<div class="row">
									<div class="col-7">
										Saldo pendiente acredit.
									</div>
									<div class="col-5">${{ $instructor->getPendingAmountToBeAccredited() }} ARS</div>
								</div>

								<a href="{{ route('instructor.balance.withdraw') }}" class="btn btn-success btn-sm" style="width: 100%; margin-top: 40px">Retirar fondos</a>
							</div>
						</div>
					</div>

					<div class="col-md-6" style="padding-left: 5px">
						<div class="card" style="height: calc(100% - 10px);">
							<div class="card-body">
								<table class="table" style="margin: 0">
									<tbody>

										<tr>
											<td style="border-top: none">
												<strong>Cuenta bancaria</strong>
												@if($instructor->bankAccount)
												<br/>
												CBU {{ $instructor->bankAccount->cbu }}
												@if(!$instructor->bankAccount->lockTimePassed())
												<i class="fas fa-exclamation-circle" style="color: #ee8918" data-toggle="tooltip" data-placement="top" title="La cuenta se agregó hace menos de {{ App\Lib\InstructorCollections\WithdrawableAccount::LOCK_TIME_DAYS }} día/s. Deberás esperar que pase este tiempo para realizar extracciones a la misma."></i>
												@endif
												@endif
											</td>
											<td style="border-top: none">
												<a href="{{ route('instructor.balance.bank-account') }}">@if(!$instructor->bankAccount) Agregar @else Modificar @endif</a>
											</td>
										</tr>

										<tr>
											<td>

												<strong>Cuenta de MercadoPago</strong>
												@if($instructor->mpAccount)
												<br/>
												<span id="mp-mail-label">
													{{ $instructor->mpAccount->email }}
													@if(!$instructor->mpAccount->lockTimePassed())
													<i class="fas fa-exclamation-circle" style="color: #ee8918" data-toggle="tooltip" data-placement="top" title="La cuenta se agregó hace menos de {{ App\Lib\InstructorCollections\WithdrawableAccount::LOCK_TIME_DAYS }} día/s. Deberás esperar que pase este tiempo para realizar extracciones a la misma."></i>
													@endif
												</span>
												@endif

												<form action="{{ url('instructor/panel/saldo/actualizar-cta-mp') }}" method="POST" id="change-mp-acc-form" style="display: none;" onkeydown="return event.key != 'Enter';">
													@csrf
													<div class="input-group input-group-sm">
														<input type="text" name="email" class="form-control" placeholder="Email" value="{{ $instructor->mpAccount ? $instructor->mpAccount->email : '' }}" autocomplete="off">
														<div class="input-group-append">
															<button type="button" class="btn btn-outline-secondary">Guardar</button>
														</div>
													</div>
												</form>

											</td>
											<td><a href="javascript:void(0);" id="update-mp-acc">@if(!$instructor->mpAccount) Agregar @else Modificar @endif</a></td>
										</tr>
									</tbody>

								</table>


							</div>
						</div>
					</div>
				</div>

				@if($wallet->collections()->pending()->count() > 0)
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
									<td>
										@if($collection->isToBank())
										Cuenta bancaria
										@else
										Cuenta Mercadopago
										@endif
									</td>
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

						<div class="row add_bottom_30">
							<div class="col-md-3">
								<h5>Movimientos</h5>
							</div>
							<div class="col-md-9">
		                    
		                        <div class="custom-select-form filter float-md-right" style="width: 125px">
		                            <select name="order" autocomplete="off">
										<option value="date_desc" {{ request()->order == "date_desc" ? "selected" : "" }}>Fecha &#8593;</option>
										<option value="date_asc" {{ request()->order == "date_asc" ? "selected" : "" }}>Fecha &#8595;</option>
										<option value="amount_desc" {{ request()->order == "price_desc" ? "selected" : "" }}>Monto &#8593;</option>
										<option value="amount_asc" {{ request()->order == "price_asc" ? "selected" : "" }}>Monto &#8595;</option>
		                            </select>
		                        </div>

		                        <div class="custom-select-form filter float-md-right mr-3" style="width: 115px">
		                            <select name="type" autocomplete="off">
										<option value="any" {{ request()->type == "any" ? "selected" : "" }}>Todos</option>
										<option value="debit" {{ request()->type == "debit" ? "selected" : "" }}>Débito</option>
										<option value="credit" {{ request()->type == "credit" ? "selected" : "" }}>Crédito</option>
		                            </select>
		                        </div>


							</div>
						</div>

						<div class="movements-table add_bottom_30">
							<div class="row head d-none d-md-flex">
								<div class="col-md-2">Fecha</div>
								<div class="col-md-3">Concepto</div>
								<div class="col-md-2">Reserva</div>
								<div class="col-md-2">Monto</div>
								<div class="col-md-3">Saldo</div>
							</div>

							@if($walletMovements->count() == 0)
								<div class="row" style="text-align: center;">No tienes movimientos</div>
							@else
								@foreach($walletMovements as $movement)

								<div class="row">

									<div class="col-md-2">
										<div class="float-left d-md-none space"></div>
										<div class="float-left d-md-none"><label>Fecha</label></div>
										<div class="float-right d-md-none space"></div>
										<div class="float-right float-md-none">{{ $movement->date->format('d/m/Y') }}</div>
									</div>
									<div class="col-md-3">
										<div class="float-left d-md-none space"></div>
										<div class="float-left d-md-none"><label>Concepto</label></div>
										<div class="float-right d-md-none space"></div>	
										<div class="float-right float-md-none">
											@if($movement->motive == App\InstructorWalletMovement::MOTIVE_RESERVATION_PAYMENT)
											Pago de reserva
											@elseif($movement->motive == App\InstructorWalletMovement::MOTIVE_COLLECTION)
											Retiro de dinero
											@endif
										</div>
									</div>
									<div class="col-md-2">
										<div class="float-left d-md-none space"></div>
										<div class="float-left d-md-none"><label>Reserva</label></div>
										<div class="float-right d-md-none space"></div>
										<div class="float-right float-md-none">
											@if($movement->motive == App\InstructorWalletMovement::MOTIVE_RESERVATION_PAYMENT)
											<a href="{{ route('instructor.reservation', $movement->reservation->code) }}">#{{ $movement->reservation->code }}</a>
											@endif
										</div>
									</div>
									<div class="col-md-2">
										<div class="float-left d-md-none space"></div>
										<div class="float-left d-md-none"><label>Monto</label></div>
										<div class="float-right d-md-none space"></div>
										<div class="float-right float-md-none">
											${{ round($movement->net_amount, 2) }}
										</div>
									</div>
									<div class="col-md-3">
										<div class="float-left d-md-none space"></div>
										<div class="float-left d-md-none"><label>Saldo</label></div>
										<div class="float-right d-md-none space"></div>
										<div class="float-right float-md-none">
											${{ round($movement->new_balance, 2) }}
										</div>
									</div>
								</div>
								@endforeach
							@endif
						</div>

						{{ $walletMovements->appends(request()->input())->links() }}

					</div>
				</div>
				@endif
				
@endsection




@section('custom-js')


<script type="text/javascript">
	
$(document).ready(function() {

	$('[data-toggle="tooltip"]').tooltip();

	$("#update-mp-acc").click(function() {
		$(this).hide();
		$("#mp-mail-label").hide();
		$("#change-mp-acc-form").show();
	});

	$("#change-mp-acc-form button").click(function() {
		if(!validateEmail($("input[name=email]").val())) {
			alert("Ingresa un e-mail válido");
			return;
		}

		if(!confirm("Si modificas tu cuenta de MercadoPago, no podrás retirar hacia esta por {{ App\Lib\InstructorCollections\WithdrawableAccount::LOCK_TIME_DAYS }} día/s. ¿Continuar?"))
			return;

		$("#change-mp-acc-form").submit();

	});

});


function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
</script>


@endsection