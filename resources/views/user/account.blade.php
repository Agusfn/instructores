@extends('layouts.main')

@section('title', 'Mi cuenta')

@section('custom-css')
<link rel="stylesheet" href="{{ asset('resources/vendor/croppie/croppie.css') }}" />
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

@section('content')

	
	<br><br>
        <div class="container margin_80_55"></div>




	<div class="container">


		    <div class="row">

                <aside class="col-lg-3" id="sidebar">
                       
                        @include('user.panel-nav-layout')
                </aside>
                <!--/aside -->
                

                <div class="col-lg-9">

                <div class="card" style="width: 28.5rem;">


                      <div class="col-lg-3"><input type="file" id="profile-pic-input" accept="image/*" autocomplete="off" @if($user->profile_picture) style="display: none" @endif>

							                       @if($user->profile_picture)
							            <div style="width: 150px;text-align: center;">
								            <img src="{{ $user->getProfilePicUrl() }}" class="profile-pic">
								            <a href="javascript:void(0);" id="change-profile-pic">Cambiar</a>
							            </div>
							                       @endif
						</div> 	                       
  
                                     <ul class="list-group list-group-flush">
                                     <li class="list-group-item">Nombre y apellido<br>
    	                             <strong>{{ $user->name.' '.$user->surname }}</strong></li>
                                     <li class="list-group-item">E-mail <br>
    	                             <strong>{{ $user->email }}</strong></li>
                                     <li class="list-group-item">Número de teléfono <br>

    	                        
							        <strong>@if($user->phone_number)
							         {{ $user->phone_number }}
							         @else
							         <a href="{{ url('panel/cuenta/modificar') }}">-Agregar- </a>
							         @endif</strong>
						             </li>
                                     </ul>

                                     <div class="card-body">
                                     <a href="{{ url('panel/cuenta/modificar') }}">Actualizar datos</a>
                                     </div>

                                     <ul class="list-group list-group-flush">
                                     <li class="list-group-item"><small>Logeado desde: {{ ucfirst($user->provider) }}</small></li>
                                     </ul>
                         </div>
                 </div>
             </div>
    </div>         
                      
        

        <br><br><br><br>               


		
            
@endsection



@section('custom-js')
<script src="{{ asset('resources/vendor/croppie/croppie.min.js') }}"></script>
<script src="{{ asset('resources/js/user-account-pg.js') }}"></script>

<script>
var app_url = "{{ config('app.url').'/' }}";
</script>
@endsection