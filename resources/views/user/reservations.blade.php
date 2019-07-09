@extends('layouts.main')


@section('title', 'Mis reservas')

@section('content')

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




</style>


<br><br>
        <div class="container margin_80_55"></div>




	<div class="container">


		    <div class="row">

                <aside class="col-lg-3" id="sidebar">
                       
                        @include('user.panel-nav-layout')
                </aside>
                <!--/aside -->



		

		<div class="col-lg-9">

			

		
				<div class="container">
  

				<div class="card" style="width: 25rem">
					
						
					
					    <ul class="list-group list-group-flush">
                                     <li>
                                     	<small>@if($reservations->count() == 0)
							            <tr><td colspan="8" style="text-align: center;">No tienes reservas</td></tr>
						                       @else
							                   @foreach($reservations as $reservation)
							               </small>
							           </li>
                        </ul>
						
                           
							<tr>
								<ul class="list-group list-group-flush"> 

									

								<div class="col-lg-12 text-center"><h5 style="margin-top: 6px;">Datos de la reserva</h5>
									<a class="float-right" href="{{ url('panel/reservas/'.$reservation->code) }}">
									<i class="fa fa-search" style="font-size: 20px" aria-hidden="true"></i></a>
								</div>	


									

								



								<li class="list-group-item">Fecha del pago:<span class="float-right">{{ $reservation->created_at->format('d/m/Y') }}</span></li>
								<li class="list-group-item">Código<span class="float-right">{{ $reservation->code }}</span></li>
								<td>
									@if($reservation->isPaymentPending())
								<li class="list-group-item">Estado	<span class="badge badge-secondary float-right">Pago pendiente</span></li>
									@elseif($reservation->isPendingConfirmation())
									<span class="badge badge-primary">Pagada - Pend. confirmación</span>
									@elseif($reservation->isFailed())
									<span class="badge badge-danger">Pago fallido</span>
									@elseif($reservation->isRejected())
									<span class="badge badge-danger">Rechazada por instructor</span>
									@elseif($reservation->isConfirmed())
									<span class="badge badge-success">Confirmada</span>
									@elseif($reservation->isConcluded())
									<span class="badge badge-success">Concluída</span>
									@elseif($reservation->isCanceled())
									<span class="badge badge-danger">Cancelada</span>
									@endif
								</td>
								<li class="list-group-item">Instructor<span class="float-right">{{ $reservation->instructor->name.' '.$reservation->instructor->surname[0].'.' }}</span></li>
								<li class="list-group-item">Fecha clase<span class="float-right">{{ $reservation->reserved_class_date->format('d/m') }}&nbsp;&nbsp;&nbsp;{{ $reservation->readableHourRange(true) }}</span></li>
								<li class="list-group-item">Personas<span class="float-right">{{ $reservation->personAmount() }}</span></li>
								<strong><li class="list-group-item">Total<span class="float-right">${{ floatval($reservation->final_price) }}</span></li></strong>
							</tr>
                           </ul>
							@endforeach
						@endif

					
				</div> <br><br><br><br>       

				{{ $reservations->links() }}
 
			
      </div>
      
	</div>
            
@endsection
