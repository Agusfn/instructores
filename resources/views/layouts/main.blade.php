<!DOCTYPE html>
<html lang="en">

<head>
	
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Instructores - Sitio de reservas para clases a medida de ski y snowboard en Cerro Catedral. San Carlos de Bariloche.">
    <meta name="author" content="Ansonika">
    <title>@yield('title') - Instructores | Ski & Snowboard</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    <!--Sacar font awesome 4 despues-->
    <link rel="stylesheet" href="{{ asset('resources/vendor/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/vendor/fontawesome-free-5.9.0-web/css/all.css') }}">
    <link href="{{ asset('resources/css/style.css?3') }}" rel="stylesheet">
	<link href="{{ asset('resources/css/vendors.css') }}" rel="stylesheet">

    <!-- YOUR CUSTOM CSS -->
    <link href="{{ asset('resources/css/custom.css?5') }}" rel="stylesheet">

    @yield('custom-css')


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
	
	
	<div id="toTop"></div><!-- Back to top button -->
	

	@yield('body-end')



	<!-- COMMON SCRIPTS -->

	<!--script src="https://kit.fontawesome.com/d20723212b.js"></script-->
    <script src="{{ asset('resources/js/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('resources/js/common_scripts.js') }}"></script>
    <script src="{{ asset('resources/js/main.js?2') }}"></script>
	<script src="{{ asset('resources/assets/validate.js') }}"></script>
    
       <!--SCROLLREVEAL-->
    <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>
    <script src="{{ asset('resources/js/Scrollreveal.js') }}"></script>


	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129656474-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-129656474-1');
	</script>
      

	@yield('custom-js')


</body>

</html>