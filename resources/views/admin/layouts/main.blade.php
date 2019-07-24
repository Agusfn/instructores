<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Ansonika">
  <title>Panel admin - Instructores.com.ar</title>
	
  <!-- Favicons-->
  <link rel="shortcut icon" href="{{ asset('resources/admin/img/favicon.ico') }}" type="image/x-icon">
  <link rel="apple-touch-icon" type="image/x-icon" href="{{ asset('resources/admin/img/apple-touch-icon-57x57-precomposed.png') }}">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{ asset('resources/admin/img/apple-touch-icon-72x72-precomposed.png') }}">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{ asset('resources/admin/img/apple-touch-icon-114x114-precomposed.png') }}">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{ asset('resources/admin/img/apple-touch-icon-144x144-precomposed.png') }}">

  <!-- GOOGLE WEB FONT -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800" rel="stylesheet">
	
  <!-- Bootstrap core CSS-->
  <link href="{{ asset('resources/admin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Main styles -->
  <link href="{{ asset('resources/admin/css/admin.css') }}" rel="stylesheet">
  <!-- Icon fonts-->
  <link href="{{ asset('resources/admin/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
  <!-- Plugin styles -->
  <link href="{{ asset('resources/admin/vendor/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">
  <!-- Your custom styles -->
  <link href="{{ asset('resources/admin/css/custom.css?3') }}" rel="stylesheet">
  @yield('custom-css')
	
</head>

<body class="fixed-nav sticky-footer" id="page-top">


  @yield('body-start')


  <!-- Navigation-->
  @include('admin.layouts.navigation')
  <!-- /Navigation-->

  <div class="content-wrapper">
    <div class="container-fluid">
    	
    	@yield('content')

    <!-- /.container-fluid-->
   	</div>
    <!-- /.container-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>© 2018 Instructores</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    @include('admin.layouts.logout-modal')

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('resources/admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('resources/admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{ asset('resources/admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Page level plugin JavaScript-->
    <script src="{{ asset('resources/admin/vendor/chart.js/Chart.js') }}"></script>
    <script src="{{ asset('resources/admin/vendor/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('resources/admin/vendor/datatables/dataTables.bootstrap4.js') }}"></script>
	<script src="{{ asset('resources/admin/vendor/jquery.selectbox-0.2.js') }}"></script>
	<script src="{{ asset('resources/admin/vendor/retina-replace.min.js') }}"></script>
	<script src="{{ asset('resources/admin/vendor/jquery.magnific-popup.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('resources/admin/js/admin.js?2') }}"></script>
	<!-- Custom scripts for this page-->
	@yield('custom-js')
    
	
</body>
</html>
