@extends('instructor.panel.layouts.main-layout')

@section('title', 'Modificar cuenta')


@section('custom-css')
<style type="text/css">


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



				<h4 class="add_bottom_30">Mi cuenta bancaria</h4>

				@if($bankAccount && $bankAccount->hasPendingCollections())
					@php($formDisabled = true)
					<div class="alert alert-warning">
						Tenés solicitudes de retiro de dinero pendientes. No podrás modificar tu cuenta bancaria hasta que estas se concluyan.
					</div>
				@else
					@php($formDisabled = false)
					<div class="alert alert-info">
						Si guardas/modificas los datos de tu cuenta bancaria, deberás esperar {{ \App\InstructorBankAccount::LOCK_TIME_DAYS }} días para poder realizar extracciones de dinero a la misma.
					</div>
				@endif

				<form action="{{ url('instructor/panel/saldo/cta-bancaria') }}" method="POST">
					@csrf

					<div class="more_padding_left add_bottom_60">

						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>CBU</strong>
							</div>
							<div class="col-5">
								<input type="text" class="form-control{{ $errors->has('cbu') ? ' is-invalid' : '' }}" value="{{ old('cbu', $bankAccount ? $bankAccount->cbu : '') }}" name="cbu" @if($formDisabled) readonly="" @endif />
							    @if ($errors->has('cbu'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('cbu') }}</strong>
							        </span>
							    @endif
							</div>
						</div>
						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Nombre del titular</strong>
							</div>
							<div class="col-5">
								<input type="text" class="form-control{{ $errors->has('holder_name') ? ' is-invalid' : '' }}" value="{{ old('holder_name', $bankAccount ? $bankAccount->holder_name : '') }}" name="holder_name" @if($formDisabled) readonly="" @endif />
							    @if ($errors->has('holder_name'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('holder_name') }}</strong>
							        </span>
							    @endif
							</div>
						</div>
						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Número de documento</strong>
							</div>
							<div class="col-5">
								<input type="text" class="form-control{{ $errors->has('document_number') ? ' is-invalid' : '' }}" value="{{ old('document_number', $bankAccount ? $bankAccount->document_number : '') }}" name="document_number" @if($formDisabled) readonly="" @endif />
							    @if ($errors->has('document_number'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('document_number') }}</strong>
							        </span>
							    @endif
							</div>
						</div>
						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>CUIL/CUIT</strong>
							</div>
							<div class="col-5">
								<input type="text" class="form-control{{ $errors->has('cuil_cuit') ? ' is-invalid' : '' }}" value="{{ old('cuil_cuit', $bankAccount ? $bankAccount->cuil_cuit : '') }}" name="cuil_cuit" @if($formDisabled) readonly="" @endif />
							    @if ($errors->has('cuil_cuit'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('cuil_cuit') }}</strong>
							        </span>
							    @endif
							</div>
						</div>

					</div>
					
					@if(!$formDisabled)
					<div class="row">
						<div class="col-11" style="text-align: right;">

							<button class="btn btn-primary"><a style="color: white" href="{{ route('instructor.balance.overview') }}">Cancelar</a>
							</button>		
							
								<button class="btn btn-primary">Guardar</button>
							
						</div>	
					</div>
					<br><br>
					@endif
				</form>

@endsection
