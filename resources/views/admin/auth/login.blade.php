

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('resources/admin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/admin/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/style.css">
    <title>Login Admin - Instructores.com.ar</title>

    <style type="text/css">
	/* sign in FORM */
	#logreg-forms{
	    width:412px;
	    margin:10vh auto;
	    background-color:#f3f3f3;
	    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
	  transition: all 0.3s cubic-bezier(.25,.8,.25,1);
	}
	#logreg-forms form {
	    width: 100%;
	    max-width: 410px;
	    padding: 15px;
	    margin: auto;
	}
	#logreg-forms .form-control {
	    position: relative;
	    box-sizing: border-box;
	    height: auto;
	    padding: 10px;
	    font-size: 16px;
	}
	#logreg-forms .form-control:focus { z-index: 2; }
	#logreg-forms .form-signin input[type="email"] {
	    margin-bottom: -1px;
	    border-bottom-right-radius: 0;
	    border-bottom-left-radius: 0;
	}
	#logreg-forms .form-signin input[type="password"] {
	    border-top-left-radius: 0;
	    border-top-right-radius: 0;
	}

	#logreg-forms .social-btn{
	    font-weight: 100;
	    color:white;
	    width:190px;
	    font-size: 0.9rem;
	}

	#logreg-forms a{
	    display: block;
	    padding-top:10px;
	    color:lightseagreen;
	}

	#logreg-form .lines{
	    width:200px;
	    border:1px solid red;
	}

	#logreg-forms button[type="submit"]{ margin-top:10px; }

	#logreg-forms .form-reset, #logreg-forms .form-signup{ display: none; }

	#logreg-forms .form-signup input { margin-bottom: 2px;}

	/* Mobile */

	@media screen and (max-width:500px){
	    #logreg-forms{
	        width:300px;
	    } 
	}
    </style>

</head>
<body>
    <div id="logreg-forms">
        <form class="form-signin" method="POST" action="{{ route('admin.login') }}">
        	@csrf

            <h1 class="h3 mb-3 font-weight-normal" style="text-align: center"> Iniciar sesión</h1>

            <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
            @if ($errors->has('email'))
                <span class="invalid-feedback" style="display: block;" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
            <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required="">
	        @if ($errors->has('password'))
	            <span class="invalid-feedback" style="display: block;" role="alert">
	                <strong>{{ $errors->first('password') }}</strong>
	            </span>
	        @endif

            <button class="btn btn-success btn-block" type="submit"><i class="fas fa-sign-in-alt"></i> Sign in</button>

        </form>

    </div>

    <script src="{{ asset('resources/admin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('resources/admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>