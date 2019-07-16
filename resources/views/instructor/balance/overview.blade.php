@extends('layouts.main')


@section('title', 'Mi saldo')



@section('content')
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
	  html main {
   	overflow-y: hidden;

   }

	.profile-pic {
		width: 150px;
		height: 150px;
		border-top-left-radius: 50% 50%;
		border-top-right-radius: 50% 50%;
		border-bottom-right-radius: 50% 50%;
		border-bottom-left-radius: 50% 50%;
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
#logo p {
    margin-top: -10px;
    font-size: medium;
    color: white!important;
}


	</style>
	
	    
		 <br><br>
        <div class="container margin_80_55"></div>



		<div class="container">


		    <div class="row">

                <aside class="col-lg-3" id="sidebar">
                       
                        @include('instructor.panel-nav-layout')
                </aside>
                <!--/aside -->


			<div class="col-lg-9">

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
						<div class="card" style="margin-bottom: 10px">
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
					<div class="col-lg-12"><h5 class="card-title">Movimientos</h5>
								@if($walletMovements->count() == 0)
									No tienes movimientos
							</div>

						<ul class="list-group list-group-flush"> 
                            
							@else
									@foreach($walletMovements as $movement)
							<li class="list-group-item">Fecha<span>{{ $movement->date->format('d/m/Y') }}</span>
							</li>

							<li class="list-group-item">Concepto<span>
											@if($movement->motive == App\InstructorWalletMovement::MOTIVE_RESERVATION_PAYMENT)
											Pago de reserva
											@elseif($movement->motive == App\InstructorWalletMovement::MOTIVE_COLLECTION)
											Retiro de dinero
											@endif
										</span>
							</li>

							<li class="list-group-item">Reserva<span>
											@if($movement->motive == App\InstructorWalletMovement::MOTIVE_RESERVATION_PAYMENT)
											<a href="{{ route('instructor.reservation', $movement->reservation->code) }}">#{{ $movement->reservation->code }}</a>
											@endif
										</span>
							</li>

							<li class="list-group-item">Monto<span>${{ round($movement->net_amount, 2) }}</span>
							</li>

							<li class="list-group-item">Saldo<span>${{ round($movement->new_balance, 2) }}</span>
							</li>
								
							</thead>
									
								
									@endforeach
								@endif
							
					    </ul>

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
            <br><br>
@endsection



@section('body-end')
@if($instructor->isApproved() && $instructor->bankAccount)
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

				@if($instructor->bankAccount->lockTimePassed())
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
						<strong>CBU:</strong> {{ $instructor->bankAccount->cbu }}<br/>
						<strong>Titular:</strong> {{ $instructor->bankAccount->holder_name }}<br/>
						<strong>Documento:</strong> {{ $instructor->bankAccount->document_number }}<br/>
						<strong>CUIL/CUIT:</strong> {{ $instructor->bankAccount->cuil_cuit }}<br/>
					</div>
				</div>
				@else
				<div class="alert alert-info">
					Tu cuenta bancaria fue recientemente configurada, debés esperar al {{ $instructor->bankAccount->unlockTime()->format('d/m/Y H:i') }} para poder retirar dinero.
				</div>
				@endif
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				@if($instructor->bankAccount->lockTimePassed())
				<button type="button" class="btn btn-primary" onclick="if(confirm('¿Confirmar?')) $('#collection-form').submit();">Confirmar</button>
				@endif
			</div>
			
		</div>

	</div>

</div>

@endif
@endsection



@section('custom-js')


<script type="text/javascript">
	
$(document).ready(function() {

	$('[data-toggle="tooltip"]').tooltip();

	@if($instructor->isApproved() && !$errors->collection->isEmpty())
	$('#collection-modal').modal("show");
	@endif


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