@extends('layouts.main')

@section('title', 'Reserva')


@section('custom-css')
<style type="text/css">
.chat
{
    list-style: none;
    margin: 0;
    padding: 0;
}

.chat li
{
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px dotted #B3A9A9;
}

.chat li.left .chat-body
{
    margin-left: 60px;
}

.chat li.right .chat-body
{
    margin-right: 60px;
}


.chat li .chat-body p
{
    margin: 0;
    color: #777777;
}

.chat .glyphicon
{
    margin-right: 5px;
}

.img-circle
{
	border-radius: 50%;
}
</style>
@endsection

@section('content')
	
	<section class="hero_in general start_bg_zoom"></section>
	<div class="container margin_60">
		


		<div class="row">

	
			@include('user.panel-sidebar')

			<div class="col-lg-9">

				<a href="{{ route('user.reservation', $reservation->code) }}"><span class="badge badge-pill badge-secondary"><i class="fa fa-arrow-left" aria-hidden="true"></i> volver a reserva</span></a>
				<h4 class="add_bottom_30">Detalles de reclamo</h4>


						
				<div class="form-group">
					<label>Creado:</label> {{ $claim->created_at->format('d/m/Y') }}
				</div>

				<div class="form-group">
					<label>Estado:</label> 
					@if($claim->isOpen()) 
					<span class="badge badge-primary">Abierto</span>
					@else 
					<span class="badge badge-secondary">Cerrado</span>
					@endif
				</div>

				<div class="form-group">
					<label>Motivo:</label> {{ $claim->motive }}
				</div>

				<div class="form-group">
					<label>Detalles: </label><br/>
					{{ $claim->description }}
				</div>

				<h5 style="margin: 30px 0">Mensajes</h5>

				<div>

					<div>
	                    <ul class="chat">

	                    	@if($messages->count() == 0)

		                    @else

		                    	@foreach($messages as $message)

		                    	@if($message->madeByUser() && $message->user_id == Auth::user()->id)
		                        <li class="right clearfix"><span class="chat-img pull-right">
		                        @else
		                        <li class="left clearfix"><span class="chat-img pull-left">
		                        @endif
		                            <img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle" />
		                        </span>
		                            <div class="chat-body clearfix">
		                                <div class="header">
		                                	@if($message->madeByUser() && $message->user_id == Auth::user()->id)
		                                    <small class=" text-muted"><span class="glyphicon glyphicon-time"></span>13 mins ago</small>
		                                    <strong class="pull-right primary-font">{{ $message->user->name.' '.$message->user->surname[0]."." }}</strong>
		                                    @else
		                                    <strong class="primary-font">
		                                    	@if($message->madeByAdmin())
		                                    	Admin
		                                    	@else
		                                    	{{ $message->instructor->name.' '.$message->instructor->surname[0]."." }}
		                                    	@endif
		                                    </strong>
		                                    <small class="pull-right text-muted"><span class="glyphicon glyphicon-time"></span>12 mins ago</small>
		                                    @endif
		                                </div>
		                                <p>
		                                	{{ $message->text }}
		                                </p>
		                            </div>
		                        </li>

		                    	@endforeach

		                    @endif

	                        
	                    </ul>
	                </div>
	                <div>
	                	<form action="{{ url('panel/reservas/'.$reservation->code.'/reclamo/mensaje') }}" method="POST">
	                		@csrf
		                    <div class="input-group">
		                        <input id="btn-input" type="text" class="form-control input-sm" name="message" placeholder="Escribe tu mensaje..." />
		                        <span class="input-group-append">
		                            <button class="btn btn-primary btn-sm" id="btn-chat">Enviar</button>
		                        </span>
		                    </div>
		                </form>
	                </div>
            	</div>

			</div>

		</div>


	</div>
            
@endsection


@section('body-end')

@endsection


@section('custom-js')


@endsection