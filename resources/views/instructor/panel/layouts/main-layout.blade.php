@extends('layouts.main')


@section('content')
	
    	<br><br>
        <div class="container margin_80_55"></div>

		<div class="container">

		    <div class="row">

                <aside class="col-lg-3 add_bottom_30" id="sidebar">
					@include('instructor.panel.layouts.nav-sidebar')
                </aside>


				<div class="col-lg-9">
					@if(!$instructor->isApproved())

						@if(!$instructor->approvalDocsSent())
							@if(!$instructor->last_docs_reject_at)
								<div class="alert alert-warning">
									Tu cuenta de instructor no está aprobada aún. Debés enviar la
									@if(request()->is('*/panel/cuenta')) documentacion solicitada @else <a href="{{ route('instructor.account') }}">documentacion solicitada</a> @endif
									para ofrecer tus servicios de instructor.
								</div>
							@else
								<div class="alert alert-warning">
									Tu cuenta de instructor no está aprobada aún. Los últimos documentos enviados no son válidos, por favor envia la
									@if(request()->is('*/panel/cuenta')) documentacion solicitada @else <a href="{{ route('instructor.account') }}">documentacion solicitada</a> @endif
									nuevamente. Motivo del último rechazo: {{ $instructor->last_docs_reject_reason }}
								</div>
							@endif
						@else
						<div class="alert alert-info">Se ha enviado la documentación para verificar y aprobar la cuenta. Estarás recibiendo un e-mail dentro de las siguientes 24hs de haberla enviado cuando la hayamos verificado.</div>
						@endif

					@endif

					@yield('panel-tab-content')
				</div>

			</div>

			<br><br>

		</div>
            
@endsection
