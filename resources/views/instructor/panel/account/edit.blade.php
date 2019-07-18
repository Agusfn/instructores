{{-- THIS VIEW DOES NOT EXTEND THE INSTRUCTOR PANEL LAYOUT --}}


@extends('layouts.main')

@section('title', 'Modificar cuenta')

@section('content')

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
    background-color: #333 !important;
    border-radius: 0;
    position: absolute;
    transition-property: transform;
    transition-duration: .15s;
    transition-timing-function: ease;
}




</style>

	<br><br>
        <div class="container margin_80_55"></div>

	<div class="container">
		


		<div class="row">
                <aside class="col-lg-3" id="sidebar">
                        
                        
                </aside>
			<div class="col-lg-12">

				
				<h4 class="add_bottom_30">Mi cuenta</h4>

				<form action="{{ url('instructor/panel/cuenta/modificar') }}" method="POST">
					@csrf

					<div class="more_padding_left add_bottom_60">

						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Nombre</strong>
							</div>
							<div class="col-5">
								<input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ $instructor->name }}" name="name" />
							    @if ($errors->has('name'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('name') }}</strong>
							        </span>
							    @endif
							</div>
						</div>

						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Apellido</strong>
							</div>
							<div class="col-5">
								<input type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" value="{{ $instructor->surname }}" name="surname" />
							    @if ($errors->has('surname'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('surname') }}</strong>
							        </span>
							    @endif
							</div>
						</div>

						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Número de teléfono</strong>
							</div>
							<div class="col-5">
								<input type="text" class="form-control{{ $errors->has('phone_number') ? ' is-invalid' : '' }}" value="{{ $instructor->phone_number }}" name="phone_number" />
							    @if ($errors->has('phone_number'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('phone_number') }}</strong>
							        </span>
							    @endif
							</div>
						</div>

					</div>
					
					

					<div class="more_padding_left add_bottom_60">
						<div class="row add_bottom_30">
							<div class="col-6">
								<strong>Cuenta de instagram (Opcional)</strong>
							</div>
							<div class="col-5">
								<input type="text" class="form-control{{ $errors->has('instagram_username') ? ' is-invalid' : '' }}" value="{{ $instructor->instagram_username }}" name="instagram_username" />
							    @if ($errors->has('instagram_username'))
							        <span class="invalid-feedback" role="alert">
							            <strong>{{ $errors->first('instagram_username') }}</strong>
							        </span>
							    @endif
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-11">

							<div style="text-align: right;">
								<button  class="btn btn-primary">
									<a style="color: white!important" href="{{ route('instructor.account') }}">Cancelar</a>
								</button>

								<button class="btn btn-primary">Guardar</button>

							</div>
						</div>	
					</div>
				</form>
			</div>

		</div>


	</div>

	<br><br>
            
@endsection
