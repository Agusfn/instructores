@extends('instructor.panel.layouts.main-layout')

@section('title', 'Mi cuenta')

@section('custom-css')
<link rel="stylesheet" href="{{ asset('resources/vendor/croppie/croppie.css') }}" />
<style type="text/css">
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
   
    color: white !important;
}
   .mm-slideout p{
    
    color: white !important;
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

.card p{
	color: black;
}
.card strong{
	color: black;
}

#datos div {
	color: black;
}
.list-group-item {
	color: black;
}
</style>
@endsection


@section('panel-tab-content')
		

				@if(!$instructor->isApproved() && !$instructor->approvalDocsSent())

					<p style="color: red!important;">Necesitamos verificar tu identidad y tu certificación como instructor para que puedas empezar a ofrecer tus servicios.</p>

					@if($instructor->phone_number && $instructor->profile_picture)

						<form style="color: black"  action="{{ url('instructor/panel/cuenta/verificar') }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="form-group">
								<label>Selecciona 2 fotos del DNI (una de cada cara), o una sola foto del pasaporte en la página de datos personales.</label>
								<input type="file" style="display: block;" name="identification_imgs[]" multiple="multiple" accept="image/*">
							    @if ($errors->has('identification_imgs'))
							        <span class="invalid-feedback" role="alert" style="display: block;">
							            <strong>{{ $errors->first('identification_imgs') }}</strong>
							        </span>
							    @elseif ($errors->has('identification_imgs.*'))
							        <span class="invalid-feedback" role="alert" style="display: block;">
							            <strong>Sólo se pueden subir imágenes JPG y PNG, de hasta 5MB cada una.</strong>
							        </span>
							    @endif
					        </span>
							</div>
							<div class="form-group">
								<label>Selecciona 2 fotos (una de cada cara) de la cédula profesional que te habilite como instructor.</label>
								<input type="file" style="display: block;" name="certificate_imgs[]" multiple="multiple" accept="image/*">
							    @if ($errors->has('certificate_imgs'))
							        <span class="invalid-feedback" role="alert" style="display: block;">
							            <strong>{{ $errors->first('certificate_imgs') }}</strong>
							        </span>
							    @elseif ($errors->has('certificate_imgs.*'))
							        <span class="invalid-feedback" role="alert" style="display: block;">
							            <strong>Sólo se pueden subir imágenes JPG y PNG, de hasta 5MB cada una.</strong>
							        </span>
							    @endif
							</div>

							<button type="submit" class="btn btn-primary">Enviar</button>
						</form>
						<br>

					@else
						<p style="color: black!important">Para comenzar, <a href="{{ url('instructor/panel/cuenta/modificar') }}">ingresá tu número de teléfono</a> y selecciona una foto de perfil. Despúes te pediremos tu documentación.</p>
					@endif

				@endif


				<div class="card bg-light mb-3" style="max-width: 100%">



					<ul class="list-group list-group-flush">
						
						<li class="list-group-item">
						
							<h5 class="card-title">Datos personales</h5>
	               	
											
							<div class="more_padding_left add_bottom_60">
								<div class="col-6"><strong>Foto de perfil</strong></div>
								<div class="col-6">

									<input type="file" id="profile-pic-input" accept="image/*" autocomplete="off" style="display: none">

									<div style="width: 150px;text-align: center;">
										<img src="{{ $instructor->getProfilePicUrl() }}" class="profile-pic"><br/>
										<a href="javascript:void(0);" id="change-profile-pic">Cambiar</a>
									</div>
								</div>
							
		                    
								<div class="row add_bottom_30" id="img-crop-box" style="display: none">
									<div class="col-sm-10">
										<div class="img-crop"></div>
									</div>

									<div class="col-sm-2">
										<button type="button" class="btn btn-default btn-primary" id="upload-profile-pic" style="position: absolute; bottom: 0">Cargar</button>
									</div>
								</div>

								<div class="row add_bottom_30">
									<div class="col-6"><strong>Cuenta de instagram</strong></div>
									<div class="col-6">
										@if($instructor->instagram_username)
										<a href="https://www.instagram.com/{{ $instructor->instagram_username }}/" target="_blank">{{ $instructor->instagram_username }}</a>
										@else
										<a href="{{ url('instructor/panel/cuenta/modificar') }}">Agregar</a>
										@endif
									</div>
								</div>
							</div>
						</li>
	 					@if($instructor->hasSocialLogin())
	                    <li class="list-group-item">
							<div class="row">
								<div class="col-6"><strong>Login cuenta</strong></div>
								<div class="col-6"><small>{{ ucfirst($instructor->provider) }}</small></div>
							</div>
					    </li>
					    @endif
	                    <li class="list-group-item">
							<div class="row">
								<div class="col-6"><strong>Nombre y apellido</strong></div>
								<div class="col-6">{{ $instructor->name.' '.$instructor->surname }}</div>
							</div>
					    </li>
	                    
	                    <li class="list-group-item">
							<div class="row">
								<div class="col-6"><strong>E-mail</strong></div>
								<div class="col-6">{{ $instructor->email }}</div>
							</div>
	                    </li>

	                    <li class="list-group-item">
							<div class="row">
								<div class="col-6"><strong>Número de teléfono</strong></div>
								<div class="col-6">
									@if($instructor->phone_number)
									{{ $instructor->phone_number }}
									@else
									<a href="{{ url('instructor/panel/cuenta/modificar') }}">Agregar</a>
									@endif
								</div>
		                    
							</div>
	                    </li>

						<li class="list-group-item">
							<div class="row">
								<div class="col-6"><strong>Cuenta aprobada</strong></div>
								<div class="col-6">
									@if($instructor->isApproved())
									Sí
									@else
										@if($instructor->approvalDocsSent())
										Pendiente
										@else
										Aprobación pendiente
										@endif
									@endif
								</div>
							</div>	
	                    </li>
	                    
	                    <li class="list-group-item">
							<div class="row">
									<div class="col-6"><strong>Tipo de documento</strong></div>
								<div class="col-6">
									@if($instructor->isApproved())
									{{ App\Instructor::idTypeName($instructor->identification_type) }}
									@else
									-
									@endif
								</div>
							</div>
					    </li>

					    <li class="list-group-item">
							<div class="row">
								<div class="col-6"><strong>Número de documento</strong></div>
								<div class="col-6">
									@if($instructor->isApproved())
									{{ $instructor->identification_number }}
									@else
									-
									@endif
								</div>
							</div>
					    </li>

					    <li class="list-group-item">
							<div class="row">
								<div class="col-6"><strong>Nivel de instructor</strong></div>
								<div class="col-6">
									@if($instructor->isApproved() && $instructor->level)
									{{ $instructor->level }}
									@else
									-
									@endif
								</div>
							</div>
					    </li>

						<li class="list-group-item" style="text-align: center;">
							<div class="container" >
								<small ><a href="{{ url('instructor/panel/cuenta/modificar') }}">Modificar Datos</a></small>
							</div>
						</li>

						@if(!$instructor->hasSocialLogin())
						<li class="list-group-item" style="text-align: center;">
							<div class="container" >
								<small ><a href="{{ route('instructor.account.change-password') }}">Cambiar contraseña</a></small>
							</div>
						</li>
						@endif
					</ul>
				</div>
         
@endsection



@section('custom-js')
<script src="{{ asset('resources/vendor/croppie/croppie.min.js') }}"></script>
<script src="{{ asset('resources/js/instructor-account-pg.js') }}"></script>

<script>
var app_url = "{{ config('app.url').'/' }}";
</script>
@endsection