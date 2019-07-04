<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Instructores - Sitio de reservas para clases a medida de ski o snowboard en Cerro Catedral. San Carlos de Bariloche.">
    <meta name="author" content="Ansonika">
    <title>@yield('title') Instructores | Ski & Snowboard</title>

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

    @yield('custom-css')

</head>

<body id="login_bg">
	
	<nav id="menu" class="fake_menu"></nav>
	
	<div id="preloader">
		<div data-loader="circle-side"></div>
	</div>
	<!-- End Preload -->
	
	<div id="login">
		<aside>
			<figure>
				<div class="row"><div id="logo">
            <div class="container">
            <a href="{{ route('home') }}"><span><h6>INSTRUCTORES</h6><p>SKI&SNOWBOARD</p></span>
                <!--
                <img src="resources/img/logo.png" width="150" height="36" data-retina="true" alt="" class="logo_normal">
                <img src="resources/img/logosticky.png" width="150" height="36" data-retina="true" alt="" class="logo_sticky">
                -->
            </a>
            </div>
        </div> 
        </div>
			</figure>
			@yield('form')
			<div class="copy">© 2018 Instructores</div>
		</aside>
	</div>
	<!-- /login -->
		
	<!-- COMMON SCRIPTS -->
    <script src="{{ asset('resources/js/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('resources/js/common_scripts.js') }}"></script>
    <script src="{{ asset('resources/js/main.js') }}"></script>
	<script src="{{ asset('resources/assets/validate.js') }}"></script>
  	@yield('custom-js')
</body>
</html>