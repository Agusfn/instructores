@extends('user.panel.layouts.main-layout')

@section('title', 'Cambiar contraseña')

@section('custom-css')
<style type="text/css">

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
    background-color: #333 !important;
    border-radius: 0;
    position: absolute;
    transition-property: transform;
    transition-duration: .15s;
    transition-timing-function: ease;
}

#logo p {
    color: white;
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


				<a href="{{ route('user.account') }}"><span class="badge badge-pill badge-secondary"><i class="fa fa-arrow-left" aria-hidden="true"></i> volver</span></a>
				<h4 class="add_bottom_30">Cambiar contraseña</h4>

				@if(\Session::has("success"))
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					Contraseña cambiada exitosamente.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				@endif

				<div class="more_padding_left add_bottom_60">
					<form action="{{ route('user.account.change-password') }}" method="POST">
						@csrf

						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Contraseña actual</strong>
							</div>
							<div class="col-5">
								<input type="password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}" name="current_password" />
							    @if ($errors->has('current_password'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('current_password') }}</strong>
							        </span>
							    @endif
							</div>
						</div>

						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Contraseña nueva</strong>
							</div>
							<div class="col-5">
								<input type="password" class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}" name="new_password" />
							    @if ($errors->has('new_password'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('new_password') }}</strong>
							        </span>
							    @endif
							</div>
						</div>
						
						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Repetir contraseña</strong>
							</div>
							<div class="col-5">
								<input type="password" class="form-control{{ $errors->has('new_password_confirmation') ? ' is-invalid' : '' }}" name="new_password_confirmation" />
							    @if ($errors->has('new_password_confirmation'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('new_password_confirmation') }}</strong>
							        </span>
							    @endif
							</div>
						</div>

						<div class="row">
							<div class="col-11">
								<div style="text-align: right;">
									<button class="btn btn-primary">Cambiar contraseña</button>
								</div>
							</div>	
						</div>
					</form>
				</div>

            
@endsection
