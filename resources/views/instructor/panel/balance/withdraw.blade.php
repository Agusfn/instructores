@extends('instructor.panel.layouts.main-layout')

@section('title', 'Retirar fondos')


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



	</style>
@endsection


@section('panel-tab-content')
				
				<h4 class="add_bottom_30">Retirar fondos</h4>

				@if(($instructor->bankAccount && $instructor->bankAccount->lockTimePassed()) || ($instructor->mpAccount && $instructor->mpAccount->lockTimePassed()))
				<label class="add_bottom_15">Selecciona la cuenta destino.</label>

				<div class="alert alert-info" id="mp_alert" style="display: none">
					Si deseás retirar hacia tu cuenta de MercadoPago, deberás afrontar los <a href="https://www.mercadopago.com.ar/ayuda/3810" target="_blank">costos de transferencia</a>.
				</div>

				<form action="{{ route('instructor.balance.withdraw') }}" method="POST">
					@csrf
					<div class="row add_bottom_30">

						@if($instructor->bankAccount && $instructor->bankAccount->lockTimePassed())
						<div class="col-md-6 add_bottom_15">
							<div class="form-check add_bottom_15">
								<input class="form-check-input" type="radio" name="destination" id="bank_radio_btn" value="bank" autocomplete="off">
								<label class="form-check-label" for="bank_radio_btn">Cuenta bancaria</label>
							</div>
							<div>
								<strong>CBU:</strong> {{ $instructor->bankAccount->cbu }}<br/>
								<strong>Titular:</strong> {{ $instructor->bankAccount->holder_name }}<br/>
								<strong>Documento:</strong> {{ $instructor->bankAccount->document_number }}<br/>
								<strong>CUIL/CUIT:</strong> {{ $instructor->bankAccount->cuil_cuit }}<br/>
							</div>
						</div>
						@endif

						@if($instructor->mpAccount && $instructor->mpAccount->lockTimePassed())
						<div class="col-md-6">
							<div class="form-check add_bottom_15">
								<input class="form-check-input" type="radio" name="destination" id="mp_radio_btn" value="mercadopago" autocomplete="off">
								<label class="form-check-label" for="mp_radio_btn">Cuenta MercadoPago</label>
							</div>
							{{ $instructor->mpAccount->email }}
						</div>
						@endif

						@if ($errors->has('destination'))
				        <span class="invalid-feedback" role="alert" style="display: block;">
				            <strong>{{ $errors->first('destination') }}</strong>
				        </span>
				    	@endif
					</div>

					<div class="add_bottom_45">
						<label>Monto en pesos</label>
						<input type="text" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{ old('amount') }}" style="width: 120px">

						@if ($errors->has('amount'))
				        <span class="invalid-feedback" role="alert">
				            <strong>{{ $errors->first('amount') }}</strong>
				        </span>
				    	@endif

					</div>

					<button class="btn btn-success">Solicitar retiro</button>
				</form>

				@else
				<div class="alert alert-warning">
					No tienes ninguna cuenta asociada para retirar fondos, o no se ha cumplido su tiempo de desbloqueo.
				</div>
				@endif

				<div class="add_bottom_30"></div>

            
@endsection

@section('custom-js')
<script type="text/javascript">

	$(document).ready(function() {

		$("input[name=destination]").change(function() {
			if($(this).val() == "mercadopago")
				$("#mp_alert").show();
			else
				$("#mp_alert").hide();
		});

	});

</script>
@endsection