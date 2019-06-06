@extends('layouts.main')



@section('content')
		<section class="hero_in hotels">
			<div class="wrapper">
				<div class="container">
					<h6 id="titulo" class="fadeInUp"><span></span>Resultados de la búsqueda</h6>
				</div>
			</div>
		</section>
		<!--/hero_in-->
		
		<div class="filters_listing sticky_horizontal">
			<div class="container">
				<ul class="clearfix">
					<li>
						<div class="switch-field">
							<input type="radio" id="all" name="listing_filter" value="all" checked>
							<label for="all">Todo</label>
							<input type="radio" id="popular" name="listing_filter" value="popular">
							<label for="popular">Populares</label>
							<input type="radio" id="latest" name="listing_filter" value="latest">
							<label for="latest">Precio</label>
						</div>
					</li>
					
				</ul>
			</div>
			<!-- /container -->
		</div>
		<!-- /filters -->
		
		
        <!-- /RESULTADO DE LA BUSQUEDA -->
		<div class="container margin_60_35" id="resultados">
			
		<div class="wrapper-grid">
			<div class="row">
				
				@if($instructorServices->count() > 0)

					@foreach($instructorServices as $service)
					<div class="col-xl-4 col-lg-6 col-md-6">
						<div class="box_grid">
							<figure>
								
								<a href="{{ route('service-page', $service->number) }}"><img src="{{ $service->instructor->profilePicUrl() }}" class="img-fluid" alt="" width="800" height="533"><div class="read_more"><span>Ver más</span></div></a>
								<small>Nivel {{ $service->instructor->level }}</small>
							</figure>
							<div class="wrapper">
								<div class="cat_star"><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i></div>
								<h3><a href="{{ route('service-page', $service->number) }}">{{ $service->instructor->name }}</a></h3>
								
								@if($service->snowboard_discipline && $service->ski_discipline)
								<p>Instructor de ski y snowboard</p>
								@elseif($service->snowboard_discipline)
								<p>Instructor de snowboard</p>
								@else
								<p>Instructor de ski</p>
								@endif
								
								<!--p>Adiomas: Ingles/español</p-->						
								<span class="price"> <strong>$3000</strong> /2 horas (por persona)</span>
							</div>
							<!--ul>
								<li><div class="score"><span>Super instructor<em>350 Reseñas</em></span><strong>9.6</strong></div></li>
							</ul-->
						</div>
					</div>
					@endforeach


				@else


				@endif

				
			</div>
			<!-- /row -->
			</div>
			<!-- /wrapper-grid -->
			
			<p id="cargarmas" class="text-center"><a href="#0" class="btn_1 rounded add_top_30">Cargar más</a></p>
			
		</div>
		<!-- /container -->
	
			</div>
			<!-- /container -->
		</div>
		<!-- /bg_color_1 -->
@endsection