@extends('layouts.main')


@if($service->snowboard_discipline && $service->ski_discipline)
    @section('title', $instructor->name.' - Instructor Ski y Snowboard')
@elseif($service->snowboard_discipline)
    @section('title', $instructor->name.' - Instructor Snowboard')
@else
    @section('title', $instructor->name.' - Instructor Ski')
@endif



@section('custom-css')
<link rel="stylesheet" type="text/css" href="{{ asset('resources/css/service-public-pg.css') }}">
<style type="text/css">
    .hero_in {
    width: 100%;
    height: 450px;
    position: relative;
    overflow: hidden;
    text-align: center;
    color: #fff;}
    header.header.sticky #logo p {
    color: black!important;
}
</style>
@endsection


@section('content')

        <section class="hero_in hotels_detail" 
        @if(isset($service->imageUrls()[0]))
        style="background-position: center center; background-size: cover; background-repeat: no-repeat; background-image: url('{{ $service->imageUrls()[0]['fullsize'] }}');"
        @endif
        >
            <div class="wrapper">
                <div class="container">
                    <h1 class="fadeIn">
                        @if($instructor->profile_picture)
                        <img src="{{ Storage::url('img/instructors/'.$instructor->profile_picture) }}" class="profile-pic">
                        @endif
                        
                    </h1><h4 style="color: whitesmoke">PERFIL</h4>
                </div>
                <span class="magnific-gallery">

                    @foreach($service->imageUrls() as $imgurl)
                        @if($loop->first)
                        <a href="{{ $imgurl['fullsize'] }}" class="btn_photos" title="Photo title" data-effect="mfp-zoom-in">Ver fotos</a>
                        @else
                        <a href="{{ $imgurl['fullsize'] }}" title="Photo title" data-effect="mfp-zoom-in"></a>
                        @endif
                    @endforeach
                </span>
            </div>
        </section>
        <!--/hero_in-->

        <div class="bg_color_1">
            <nav class="secondary_nav sticky_horizontal">
                <div class="container">
                    <ul class="clearfix">
                        <li><a href="#description" class="active">Descripción</a></li>
                        {{--<li><a href="#reviews">Comentarios</a></li>--}}
                        <li><a href="#sidebar">Reservar</a></li>
                    </ul>
                </div>
            </nav>

            <div class="container margin_60_35">

                @include('layouts.errors')

                <div class="row">
                    <div class="col-lg-8">
                        <section id="description">

                            <h2>Descripción</h2>
                            <p>{!! nl2br(e($service->description)) !!}</p>
                            
                            <hr>

                            <h3 style="margin-bottom: 15px;">Características</h3>

                            <div class="row features">
                               
                                <div class="col-md-6">
                                    <ul>
                                        @if($service->allows_groups)
                                            <li><i class="fas fa-users"></i><span>Admite clases grupales de hasta {{ $service->max_group_size }} personas<span></li>
                                        @endif

                                        @if($service->offered_to_adults && $service->offered_to_kids)
                                        <li><i class="fas fa-user-friends"></i><span>Ofrece clases a adultos y niños</span></li>
                                        @elseif($service->offered_to_adults)
                                        <li><i class="fas fa-male"></i><span>Ofrece sólo clases a adultos</span></li>
                                        @else
                                        <li><i class="fas fa-child"></i><span>Ofrece clases a niños</span></li>
                                        @endif

                                        @if($service->ski_discipline)
                                        <li><i class="fas fa-skiing"></i><span>Clases de ski</span></li>
                                        @endif

                                        @if($service->snowboard_discipline)
                                        <li><i class="far fa-snowboarding" style="font-weight: 900;"></i><span>Clases de snowboard</span></li>
                                        @endif 

                                        @if($instructor->level)
                                        <li><i class="fas fa-graduation-cap"></i><span>Instructor nivel {{ $instructor->level }}</span></li>
                                        @endif
                                    </ul>
                                </div>

                                <div class="col-md-6">
                                    <ul>
                                        @foreach($service->featuresArray() as $feature)
                                            <li><i class="far fa-dot-circle" style="font-size: 16px;"></i><span>{{ $feature }}</span></li>
                                        @endforeach
                                    </ul>
                                </div>

                                {{--<div class="col-lg-6">
                                    <ul class="bullets">
                                        @foreach($service->featuresArray() as $feature)
                                            @if($loop->odd)
                                            <li>{{ $feature }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-lg-6">
                                    <ul class="bullets">
                                        @foreach($service->featuresArray() as $feature)
                                            @if($loop->even)
                                            <li>{{ $feature }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>--}}
                            </div>
                            <!-- /row -->

                            @if($instructor->instagram_username)
                            <hr>
                            <h3>Cuenta de Instagram</h3>                            
                            <div id="instagram-feed" class="clearfix"></div>
                            <hr>
                            @endif
                            
                        
                        </section>
                        <!-- /section -->
                    
                        <section id="reviews">
                            <h2>Comentarios</h2>
                            <div class="reviews-container">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div id="review_summary">
                                            <strong>{{ $instructor->review_stars_score ?: '-' }}</strong>
                                            @if($instructor->review_stars_score > 4.5)<em>Superinstructor</em>@endif
                                            <small>{{ $reviews->count() }} @if($reviews->count() > 1) comentarios @else comentario @endif</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">

                                        @foreach($instructor->getGroupedReviewScore() as $starCount => $starRatingInfo)
                                        <div class="row">
                                            <div class="col-lg-10 col-9">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $starRatingInfo['percentage'] }}%" aria-valuenow="{{ $starRatingInfo['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-3"><small><strong>{{ $starCount }} @if($starCount > 1) estrellas @else estrella @endif</strong></small></div>
                                        </div>
                                        @endforeach
                                        
                                    </div>
                                </div>
                                <!-- /row -->
                            </div>

                            <hr>

                            <div class="reviews-container">

                                @if($reviews->count() > 0)
                                    @foreach($reviews as $review)
                                    <div class="review-box clearfix">
                                        <figure class="rev-thumb"><img src="{{ $review->user->getProfilePicUrl() }}" alt="{{ $review->user->name }}">
                                        </figure>
                                        <div class="rev-content">
                                            <div class="rating">
                                                @for($i=1; $i<=5; $i++)
                                                <i class="icon_star {{ $i <= $review->rating_stars ? 'voted' : '' }}"></i>
                                                @endfor
                                            </div>
                                            <div class="rev-info">
                                                {{ $review->user->name }} – {{ $review->created_at->day }} {{ $review->created_at->shortMonthName }}, {{ $review->created_at->year }}.
                                            </div>
                                            <div class="rev-text">
                                                <p>
                                                    {{ $review->comment }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <h4>Este instructor aún no tiene comentarios.</h4>
                                @endif
                                
                                
                            </div>
                            <!-- /review-container -->
                        </section>


                        @user
                        @if(Auth::user()->canLeaveReviewToInstructor($instructor->id))

                        <hr>

                        <div class="add-review">
                            <h5>Dejar un comentario</h5>
                            <form action="{{ url('instructor/'.$service->number.'/comentario') }}" method="POST">
                                @csrf
                                <input type="hidden" name="service_number" value="{{ $service->number }}">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Calificación </label>
                                        <div class="custom-select-form">
                                            <select name="rating" id="rating_review" class="form-control wide{{ $errors->review->has('rating') ? ' is-invalid' : '' }}">
                                                <option value="-1" selected>Seleccionar</option>
                                                <option value="1">1 (bajo)</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5 (alto)</option>
                                            </select>
                                            @if ($errors->review->has('rating'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->review->first('rating') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Comentario</label>
                                        <textarea name="comment" id="review_text" class="form-control{{ $errors->review->has('comment') ? ' is-invalid' : '' }}" style="height:130px;"></textarea>
                                        @if ($errors->review->has('comment'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->review->first('comment') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-12 add_top_20">
                                        <input type="submit" value="Enviar" class="btn_1" id="submit-review">
                                    </div>
                                </div>
                            </form>
                        </div>

                        @endif
                        @enduser


                    </div>
                    <!-- /col -->
                    
                    <aside class="col-lg-4" id="sidebar">
                        <div class="box_detail booking">
                            <div class="price">
                                <div id="price-from-label">Desde<br/></div>
                                <span><span id="price-per-block"></span><small>/ bloque 2hs</small></span>
                                @if($instructor->review_stars_score)
                                <div class="score">
                                    @if($instructor->review_stars_score > 4.5)
                                        <span>Excelente<em>{{ $reviews->count() }} votos</em></span>
                                    @else
                                        <span><em>{{ $reviews->count() }} votos</em><br/></span>
                                    @endif
                                    
                                    <strong>{{ $instructor->review_stars_score }}</strong>
                                </div>
                                @endif
                            </div>

                            <form method="GET" action="{{ url('reservar/'.$service->number) }}" id="book-form">
                                
                                <div class="form-group" id="discipline-selection">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($service->ski_discipline)
                                        <button type="button" class="btn btn-secondary" data-discipline="ski">Ski</button>
                                        @endif
                                        @if($service->snowboard_discipline)
                                        <button type="button" class="btn btn-secondary" data-discipline="snowboard">Snowboard</button>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input class="form-control" type="text" name="date" id="date-picker-input" readonly="true" autocomplete="off" placeholder="Fecha..">
                                    <i class="icon_calendar"></i>
                                </div>

                                <div class="form-group" id="hour-selection" style="display: none">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-secondary" id="hour-block-0" data-hour-block="0">9-11hs</button>
                                        <button type="button" class="btn btn-secondary" id="hour-block-1" data-hour-block="1">11-13hs</button>
                                        <button type="button" class="btn btn-secondary" id="hour-block-2" data-hour-block="2">13-15hs</button>
                                        <button type="button" class="btn btn-secondary" id="hour-block-3" data-hour-block="3">15-17hs</button>
                                    </div>
                                </div>

                                <div class="panel-dropdown">
                                    <a href="#">Personas<span class="qtyTotal">1</span></a>
                                    <div class="panel-dropdown-content right">
                                        @if($service->offered_to_adults)
                                        <div class="qtyButtons">
                                            <label>Adultos</label>
                                            <input type="text" name="adults_amount" class="qtyInput" value="{{ $setInitialDate ? $input['adults'] : 1 }}" data-max="@if($service->allows_groups) {{ $service->max_group_size }} @else 1 @endif" @if($service->offered_to_kids) data-min="0" @else data-min="1" @endif autocomplete="off">
                                        </div>
                                        @endif
                                        @if($service->offered_to_kids)
                                        <div class="qtyButtons">
                                            <label>Niños</label>
                                            <input type="text" name="kids_amount" class="qtyInput" value="{{ $setInitialDate ? $input['kids'] : 0 }}" data-max="@if($service->allows_groups) {{ $service->max_group_size }} @else 1 @endif" @if($service->offered_to_adults) data-min="0" @else data-min="1" @endif autocomplete="off">
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <input type="hidden" name="discipline" value="" autocomplete="off">
                                <input type="hidden" name="t_start" value="" autocomplete="off">
                                <input type="hidden" name="t_end" value="" autocomplete="off">
                                <input type="hidden" name="last_price" value="" autocomplete="off">
                            </form>

                            <div class="total-summary">
                                
                                <table class="table table-borderless table-sm">
                                    <tbody></tbody>
                                </table>

                            </div>

                            
                        </div>
                        <button type="button" id="book-btn" class="add_top_30 btn_1 full-width purchase">Reservar</button>
                            
                        <div class="text-center"><small>No se carga dinero en esta etapa</small></div>
                        
                        <!--ul class="share-buttons">
                            <li><a class="fb-share" href="#0"><i class="social_facebook"></i> Compartir</a></li>
                            
                            <li><a class="gplus-share" href="#0"><i class="social_googleplus"></i> Compartir</a></li>
                        </ul-->
                    </aside>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /bg_color_1 -->

@endsection



@section('custom-js')



<!--script src="http://maps.googleapis.com/maps/api/js"></script-->
<script src="{{ asset('resources/js/map_single_hotel.js') }}"></script>
<script src="{{ asset('resources/js/infobox.js') }}"></script>
<script src="{{ asset('resources/js/input_qty.js') }}"></script>
<script src="{{ asset('resources/js/service-public-pg.js?3') }}"></script>

<script>

var start_date = "{{ $activityStartDate->isPast() ? (new Carbon\Carbon())->format('d/m/Y') : $activityStartDate->format('d/m/Y') }}";
var end_date = "{{ $activityEndDate->format('d/m/Y') }}";

var app_url = "{{ config('app.url').'/' }}";
var serv_number = {{ $service->number }};
var group_surcharges = {!! json_encode($service->getGroupSurcharges()) !!};
var max_group_size = {{ $service->allows_groups ? $service->max_group_size : "1" }};

@if($setInitialDate)
var initialSearchedDate = "{{ $input['date'] }}";
@endif

$(document).ready(function() {

    @if( (!$service->ski_discipline && $service->snowboard_discipline) || ($service->ski_discipline && !$service->snowboard_discipline) )
    $("#discipline-selection > div > .btn:first").trigger("click");
    @else
        @if($setInitialDate)
            @if($input["discipline"] == "ski")
            $("#discipline-selection > div > .btn:first").trigger("click");
            @else
            $("#discipline-selection > div > .btn:nth-child(2)").trigger("click");
            @endif
        @endif
    @endif

});
</script>


@if($instructor->instagram_username)
<script>
$(window).on('load', function(){
        "use strict";
        $.instagramFeed({
            'username': '{{ $instructor->instagram_username }}',
            'container': "#instagram-feed",
            'display_profile': false,
            'display_biography': false,
            'display_gallery': true,
            'get_raw_json': false,
            'callback': null,
            'styling': true,
            'items': 12,
            'items_per_row': 6,
            'margin': 1 
        });
    });
</script>
@endif


@endsection