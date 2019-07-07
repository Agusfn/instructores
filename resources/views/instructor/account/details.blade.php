@extends('layouts.main')

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


@section('content')
	
    <br><br>
        <div class="container margin_80_55"></div>


		<div class="container">


		    <div class="row">

                <aside class="col-lg-3" id="sidebar">
                       
                        @include('instructor.panel-nav-layout')
                </aside>
                <!--/aside -->

			<div class="col-lg-9">

				@if(!$instructor->isApproved())
					@if(!$instructor->approvalDocsSent())
					<div class="alert alert-warning">Tu cuenta de instructor no está aprobada aún. Debés enviar la documentacion solicitada para ofrecer tus servicios de instructor.</div>
					@else
					<div class="alert alert-info">Se ha enviado la documentación para verificar y aprobar la cuenta. Estarás recibiendo un e-mail dentro de las siguientes 24hs de haberla enviado cuando la hayamos verificado.</div>
					@endif
				@endif





					@if(!$instructor->approvalDocsSent())

					
					<p style="color: red!important;">Necesitamos verificar tu identidad y tu certificación como instructor para que puedas empezar a ofrecer tus servicios.</p>

					@if($instructor->phone_number && $instructor->profile_picture)

						<form action="{{ url('instructor/panel/cuenta/verificar') }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="form-group">
								<label>Fotos de ambas caras del DNI, o una foto del pasaporte</label>
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
								<label>Fotos de ambas caras del certificado profesional emitido por la AADIDESS</label>
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

					@else
						<p style="color: black!important">Para comenzar, <a href="{{ url('instructor/panel/cuenta/modificar') }}">ingresá tu número de teléfono</a> y seleccioná una foto de perfil. Despúes te pediremos tu documentación.</p>
					@endif


				@endif




				<div class="card bg-light mb-3" style="max-width: 100%">



				<ul class="list-group list-group-flush">
					<li class="list-group-item"><h5 class="card-title">Datos personales <span style="font-size: 14px;font-weight: normal;">- <a href="{{ url('instructor/panel/cuenta/modificar') }}">Actualizar datos</a></span>
					</h5>
               
					

				<div class="more_padding_left add_bottom_60" id="datos">
					
					<div class="row add_bottom_30">
						<div class="col-6"><strong>Foto de perfil</strong></div>
						<div class="col-6">

							<input type="file" id="profile-pic-input" accept="image/*" autocomplete="off" @if($instructor->profile_picture) style="display: none" @endif>

							@if($instructor->profile_picture)
							<div style="width: 150px;text-align: center;">
								<img src="{{ $instructor->getProfilePicUrl() }}" class="profile-pic"><br/>
								<a href="javascript:void(0);" id="change-profile-pic">Cambiar</a>
							</div>
							@endif
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

					</li>
 
                    <li class="list-group-item">
				   

					<div class="row">
						<div class="col-6"><strong>Login cuenta</strong></div>
						<div class="col-6">{{ ucfirst($instructor->provider) }}</div>
					</div>

				    </li>
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
							@if($instructor->isApproved())
							{{ $instructor->level }}
							@else
							-
							@endif
						</div>
					</div>
				    </li>
				</div>

			      
				</div>	



				 </ul>


			</div>

		</div>


	

            
@endsection

@section('custom-js')
<script src="{{ asset('resources/vendor/croppie/croppie.min.js') }}"></script>
<script src="{{ asset('resources/js/instructor-account-pg.js') }}"></script>

<script>

var app_url = "{{ config('app.url').'/' }}";
//var url = "{{ Storage::url('img/instructors/DSC01022.jpg') }}";


</script>
@endsection