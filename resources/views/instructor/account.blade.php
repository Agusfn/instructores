@extends('layouts.main')


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
</style>
@endsection


@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

			@include('instructor.panel-nav-layout')

			<div class="col-md-9">

				@if(!$instructor->isApproved())
					@if(!$instructor->approvalDocsSent())
					<div class="alert alert-warning">Tu cuenta de instructor no está aprobada aún. Debés enviar la documentacion solicitada para ofrecer tus servicios de instructor.</div>
					@else
					<div class="alert alert-info">Se ha enviado la documentación para verificar y aprobar la cuenta. Estarás recibiendo un e-mail dentro de las siguientes 24hs de haberla enviado cuando la hayamos verificado.</div>
					@endif
				@endif

				<h4 class="add_bottom_30">Mi cuenta</h4>


				<div class="more_padding_left add_bottom_60">
					<div class="row add_bottom_30">
						<div class="col-6"><strong>Nombre y apellido</strong></div>
						<div class="col-6">{{ $instructor->name.' '.$instructor->surname }}</div>
					</div>
					<div class="row add_bottom_30">
						<div class="col-6"><strong>E-mail</strong></div>
						<div class="col-6">{{ $instructor->email }}</div>
					</div>
					<div class="row add_bottom_30">
						<div class="col-6"><strong>Contraseña</strong></div>
						<div class="col-6">********** <span style="font-size:12px">(<a href="{{ url('instructor/panel/cuenta/password') }}">cambiar</a>)</span></div>
					</div>
					<div class="row add_bottom_30">
						<div class="col-6"><strong>Cuenta aprobada</strong></div>
						<div class="col-6">
							@if($instructor->isApproved())
							Sí
							@else
								@if($instructor->approvalDocsSent())
								Pendiente
								@else
								No
								@endif
							@endif
						</div>
					</div>						
					<div class="row add_bottom_30">
						<div class="col-6"><strong>Número de teléfono</strong></div>
						<div class="col-6">
							@if($instructor->phone_number)
							<div>
								{{ $instructor->phone_number }}
								<span style="font-size:12px">(<a href="javascript:void(0);" onclick="$(this).closest('div').hide();$('#change-phone-form').show();">cambiar</a>)</span>
							</div>
							@else
							<a href="javascript:void(0);" onclick="$(this).hide();$('#change-phone-form').show();">Agregar</a>
							@endif
							<form id="change-phone-form" style="display: none" method="POST" action="{{ url('instructor/panel/cuenta/cambiar_tel') }}">
								@csrf
								<input type="text" value="{{ $instructor->phone_number }}" name="phone_number" class="form-control form-control-sm" style="width: 150px; display: inline-block;">
								<button type="submit" class="btn btn-primary btn-sm">Aplicar</button>
							</form>
						</div>
					</div>
					<div class="row add_bottom_30">
							<div class="col-6"><strong>Tipo de documento</strong></div>
						<div class="col-6">
							@if($instructor->isApproved())
							{{ App\Instructor::idTypeName($instructor->identification_type) }}
							@else
							-
							@endif
						</div>
					</div>
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
				</div>

				<h4 class="add_bottom_30">Mi perfil</h4>

				<div class="more_padding_left add_bottom_60">
					<div class="row add_bottom_30">
						<div class="col-6"><strong>Foto de perfil</strong></div>
						<div class="col-6">

							<input type="file" id="profile-pic-input" accept="image/*" autocomplete="off" @if($instructor->profile_picture) style="display: none" @endif>

							@if($instructor->profile_picture)
							<div style="width: 150px;text-align: center;">
								<img src="{{ $instructor->profilePicUrl() }}" class="profile-pic"><br/>
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
							<div>
								<a href="https://www.instagram.com/{{ $instructor->instagram_username }}/" target="_blank">{{ $instructor->instagram_username }}</a>
								<span style="font-size:12px">(<a href="javascript:void(0);" onclick="$(this).closest('div').hide();$('#instagram-form').show();">cambiar</a>)</span>
							</div>
							@else
							<a href="javascript:void(0);" onclick="$(this).hide();$('#instagram-form').show();">Agregar</a>
							@endif

							<form id="instagram-form" style="display: none" method="POST" action="{{ url('instructor/panel/cuenta/cambiar_instagram') }}">
								@csrf
								<input type="text" value="{{ $instructor->instagram_username }}" name="instagram_username" class="form-control form-control-sm" style="width: 150px; display: inline-block;">
								<button type="submit" class="btn btn-primary btn-sm">Aplicar</button>
							</form>

						</div>
					</div>
				</div>	



				@if(!$instructor->approvalDocsSent())

					<hr>
					<h4 style="margin: 20px 0 30px">Verificar cuenta</h4>

					<p>Necesitamos verificar tu identidad y tu certificación como instructor para que puedas empezar a ofrecer tus servicios.</p>

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
								<label>Fotos de ambas caras del certificado profesional</label>
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
						<h6>Para comenzar, ingresá tu número de teléfono y seleccioná una foto de perfil.</h6>
					@endif


				@endif


			</div>

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