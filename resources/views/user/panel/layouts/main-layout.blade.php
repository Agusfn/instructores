@extends('layouts.main')


@section('content')
	
    	<br><br>
        <div class="margin_80_55"></div>

		<div class="container">

		    <div class="row" style="padding-bottom: 40px">

                <aside class="col-lg-3 add_bottom_30" id="sidebar">
					@include('user.panel.layouts.nav-sidebar')
                </aside>


				<div class="col-lg-9">
					@yield('panel-tab-content')
				</div>

			</div>

		</div>
            
@endsection
