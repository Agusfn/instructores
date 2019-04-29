<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Instructores - Sitio de reservas para clases a medida de ski o snowboard.">
    <meta name="author" content="Ansonika">
    <title>Instructores | Ski & Snowboard</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="{{ asset('resources/img/favicon.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="{{ asset('resources/img/apple-touch-icon-57x57-precomposed.png') }}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{ asset('resources/img/apple-touch-icon-72x72-precomposed.png') }}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{ asset('resources/img/apple-touch-icon-114x114-precomposed.png') }}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{ asset('resources/img/apple-touch-icon-144x144-precomposed.png') }}">

    <!-- GOOGLE WEB FONT -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800" rel="stylesheet">

    <!-- BASE CSS -->
    <link href="{{ asset('resources/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/css/style.css') }}" rel="stylesheet">
	<link href="{{ asset('resources/css/vendors.css') }}" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="{{ asset('resources/css/custom.css') }}" rel="stylesheet">

</head>

<body>
		
	<div id="page">
		
		@include('layouts.header')
		<!-- /header -->
		
		<main>
			@yield('content')
		</main>
		<!-- /main -->

		@include('layouts.footer')
		<!--/footer-->
	</div>
	<!-- page -->
	
	
	<!-- Sign In Popup -->
	<div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide">
		<div class="small-dialog-header">
			<h3>Ingresar</h3>
		</div>
		<form>
			<div class="sign-in-wrapper">
				<a href="#0" class="social_bt facebook">Entrar con Facebook</a>
				<a href="#0" class="social_bt google">Entrar con Google</a>
				<div class="divider"><span>O</span></div>
				<div class="form-group">
					<label>Dirección de correo electronico</label>
					<input type="email" class="form-control" name="email" id="email">
					<i class="icon_mail_alt"></i>
				</div>
				<div class="form-group">
					<label>Contraseña</label>
					<input type="password" class="form-control" name="password" id="password" value="">
					<i class="icon_lock_alt"></i>
				</div>
				<div class="clearfix add_bottom_15">
					<div class="checkboxes float-left">
						<label class="container_check">Recordarme
						  <input type="checkbox">
						  <span class="checkmark"></span>
						</label>
					</div>
					<div class="float-right mt-1"><a id="forgot" href="javascript:void(0);">Olvidaste la contraseña?</a></div>
				</div>
				<div class="text-center"><input type="submit" value="Ingresar" class="btn_1 full-width"></div>
				<div class="text-center">
					No tenes una cuenta? <a href="register.html">Registrarme</a>
				</div>
				<div id="forgot_pw">
					<div class="form-group">
						<label>Por favor ingrese su email</label>
						<input type="email" class="form-control" name="email_forgot" id="email_forgot">
						<i class="icon_mail_alt"></i>
					</div>
					<p>Recibiras un email con un link para resetear tu clave</p>
					<div class="text-center"><input type="submit" value="Reset Password" class="btn_1"></div>
				</div>
			</div>
		</form>
		<!--form -->
	</div>
	<!-- /Sign In Popup -->
	
	<div id="toTop"></div><!-- Back to top button -->
	
	<!-- COMMON SCRIPTS -->
    <script src="{{ asset('resources/js/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('resources/js/common_scripts.js') }}"></script>
    <script src="{{ asset('resources/js/main.js') }}"></script>
	<script src="{{ asset('resources/assets/validate.js') }}"></script>

	@yield('custom-js')

</body>
</html>