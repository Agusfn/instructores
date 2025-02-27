@extends('layouts.main')


@section('title', 'Buscar clases')



@section('content')


<style type="text/css">
main{ 
	overflow-y: hidden;
	overflow-y: hidden;
 }

.mm-slideout {
    background-color: #299aea;
    height: 83px;
}

.filters_listing {
    padding: 25px 0 5px 0 !important;
    background: #fff;
    border-bottom: 1px solid #ededed;
    z-index: 999999 !important;
    position: relative;
}



</style>

	
	
    <br><br><br>
	<div class="filters_listing sticky_horizontal">
		<div class="container">
			<ul class="clearfix">
				<li>
					<div class="switch-field">
						<input type="radio" id="all" name="sort_results" value="default" @if($searchParams['sort'] == 'default') checked @endif>
						<label for="all">Todo</label>
						<input type="radio" id="popular" name="sort_results" value="popular" @if($searchParams['sort'] == 'popular') checked @endif>
						<label for="popular">Populares</label>
						<input type="radio" id="latest" name="sort_results" value="lower_price" @if($searchParams['sort'] == 'lower_price') checked @endif>
						<label for="latest">Precio</label>
					</div>
				</li>
				
			</ul>
		</div>
		<!-- /container -->
	</div>
	<!-- /filters -->
	
	
    <!-- /RESULTADO DE LA BUSQUEDA -->
	<div class="container margin_60_35">
		
		<div id="no-results" style="display: none;text-align: center;">
			<h4>No se encontraron resultados</h4>
			<a href="{{ route('home') }}">Volver a buscar</a>
		</div>

		<div class="wrapper-grid" id="search-results">
			<div class="row">
			
			</div>
		</div>
		
		<p class="text-center"><button type="button" id="load-more" class="btn_1 rounded add_top_30">Cargar más</button></p>
		
	</div>
@endsection

@section('custom-js')
<script type="text/javascript" src="{{ asset('resources/js/search-pg.js?3') }}"></script>
<script>
	var app_url = "{{ config('app.url').'/' }}";
	var sort = "{{ $searchParams['sort'] }}";
	var discipline = "{{ $searchParams['discipline'] }}";
	var date = "{{ $searchParams['date'] }}";
	var qty_adults = {{ $searchParams['qty_adults'] }};
	var qty_kids = {{ $searchParams['qty_kids'] }};
</script>
@endsection