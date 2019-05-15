@extends('layouts.main')


@section('custom-css')
<style type="text/css">
.profile-pic {
    width: 200px;
    height: 200px;
    margin-bottom: 20px;
    border-top-left-radius: 50% 50%;
    border-top-right-radius: 50% 50%;
    border-bottom-right-radius: 50% 50%;
    border-bottom-left-radius: 50% 50%;
}

#date-picker-input {
    background-color: inherit;
}

#hour-selection {
    width: 100%;
}
#hour-selection .btn {
    width:25%;
}

.daterangepicker {
    width: auto;
}

.daterangepicker .calendar.left.single {
    max-width: none
}

.daterangepicker .calendar-table td, .daterangepicker .calendar-table th {
    min-width: 45px;
    width: 45px;
    height: 45px;
    font-size: 13px;
    cursor: inherit;
}

.daterangepicker .calendar-table td.available {
    cursor: pointer;
}

.date-min-price {
    font-size:9px;
    line-height: 13px;
    color: #308ad5;
}

</style>

@endsection


@section('content')

        <section class="hero_in hotels_detail" 
        @if(isset($service->imageUrls()[0]))
        style="background-position: center center; background-size: cover; background-repeat: no-repeat; background-image: url('{{ $service->imageUrls()[0] }}'); "
        @endif
        >
            <div class="wrapper">
                <div class="container">
                    <h1 class="fadeInUp">
                        @if($instructor->profile_picture)
                        <img src="{{ Storage::url('img/instructors/'.$instructor->profile_picture) }}" class="profile-pic">
                        @endif
                        <span></span>Perfil
                    </h1>
                </div>
                <span class="magnific-gallery">

                    @foreach($service->imageUrls() as $url)
                        @if($loop->first)
                        <a href="{{ $url }}" class="btn_photos" title="Photo title" data-effect="mfp-zoom-in">Ver fotos</a>
                        @else
                        <a href="{{ $url }}" title="Photo title" data-effect="mfp-zoom-in"></a>
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
                        <li><a href="#reviews">Comentarios</a></li>
                        <li><a href="#sidebar">Reservar</a></li>
                    </ul>
                </div>
            </nav>
            <div class="container margin_60_35">
                <div class="row">
                    <div class="col-lg-8">
                        <section id="description">
                            <h2>Descripción</h2>
                            <p>{!! nl2br(e($service->description)) !!}</p>
                            <div class="row">
                                <div class="col-lg-6">
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
                                </div>
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
                                            <strong>8.5</strong>
                                            <em>Superinstructor</em>
                                            <small>4 comentarios</small>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="row">
                                            <div class="col-lg-10 col-9">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-3"><small><strong>5 stars</strong></small></div>
                                        </div>
                                        <!-- /row -->
                                        <div class="row">
                                            <div class="col-lg-10 col-9">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 95%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-3"><small><strong>4 stars</strong></small></div>
                                        </div>
                                        <!-- /row -->
                                        <div class="row">
                                            <div class="col-lg-10 col-9">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-3"><small><strong>3 stars</strong></small></div>
                                        </div>
                                        <!-- /row -->
                                        <div class="row">
                                            <div class="col-lg-10 col-9">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-3"><small><strong>2 stars</strong></small></div>
                                        </div>
                                        <!-- /row -->
                                        <div class="row">
                                            <div class="col-lg-10 col-9">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-3"><small><strong>1 stars</strong></small></div>
                                        </div>
                                        <!-- /row -->
                                    </div>
                                </div>
                                <!-- /row -->
                            </div>

                            <hr>

                            <div class="reviews-container">

                                <div class="review-box clearfix">
                                    <figure class="rev-thumb"><img src="{{ asset('resources/img/avatar1.jpg') }}" alt="">
                                    </figure>
                                    <div class="rev-content">
                                        <div class="rating">
                                            <i class="icon_star voted"></i><i class="icon_star voted"></i><i class="icon_star voted"></i><i class="icon_star voted"></i><i class="icon_star"></i>
                                        </div>
                                        <div class="rev-info">
                                            Juan – Julio 03, 2018:
                                        </div>
                                        <div class="rev-text">
                                            <p>
                                                Muy buena onda! gran instructor.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- /review-box -->
                                <div class="review-box clearfix">
                                    <figure class="rev-thumb"><img src="{{ asset('resources/img/avatar2.jpg') }}" alt="">
                                    </figure>
                                    <div class="rev-content">
                                        <div class="rating">
                                            <i class="icon-star voted"></i><i class="icon_star voted"></i><i class="icon_star voted"></i><i class="icon_star voted"></i><i class="icon_star"></i>
                                        </div>
                                        <div class="rev-info">
                                            Lio Messi – Julio 21, 2018:
                                        </div>
                                        <div class="rev-text">
                                            <p>
                                                Ismael es un monstro. -10-
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- /review-box -->
                                
                            </div>
                            <!-- /review-container -->
                        </section>
                        <!-- /section -->
                        <hr>

                            <div class="add-review">
                                <h5>Dejar un comentario</h5>
                                <form>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label>Nombre y apellido*</label>
                                            <input type="text" name="name_review" id="name_review" placeholder="" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Email *</label>
                                            <input type="email" name="email_review" id="email_review" class="form-control">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Calificación </label>
                                            <div class="custom-select-form">
                                            <select name="rating_review" id="rating_review" class="wide">
                                                <option value="1">1 (bajo)</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5" selected>5 (medio)</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10 (alto)</option>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Comentario</label>
                                            <textarea name="review_text" id="review_text" class="form-control" style="height:130px;"></textarea>
                                        </div>
                                        <div class="form-group col-md-12 add_top_20">
                                            <input type="submit" value="Submit" class="btn_1" id="submit-review">
                                        </div>
                                    </div>
                                </form>
                            </div>
                    </div>
                    <!-- /col -->
                    
                    <aside class="col-lg-4" id="sidebar">
                        <div class="box_detail booking">
                            <div class="price">
                                <span><span id="price-per-block"></span><small>/ bloque 2hs</small></span>
                                <div class="score"><span>Excelente<em>350 votos</em></span><strong>9.6</strong></div>
                            </div>

                            <div class="form-group">
                                <input class="form-control" type="text" name="dates" id="date-picker-input" readonly="true" autocomplete="off" placeholder="Fecha..">
                                <i class="icon_calendar"></i>
                            </div>

                            <div class="form-group" style="text-align: center;">
                                <div class="btn-group btn-group-sm" id="hour-selection" role="group">
                                    <button type="button" class="btn btn-secondary" id="hour-block-0">9-11hs</button>
                                    <button type="button" class="btn btn-secondary" id="hour-block-1">11-13hs</button>
                                    <button type="button" class="btn btn-secondary" id="hour-block-2">13-15hs</button>
                                    <button type="button" class="btn btn-secondary" id="hour-block-3">15-17hs</button>
                                </div>
                            </div>

                            <div class="panel-dropdown">
                                <a href="#">Personas<span class="qtyTotal">1</span></a>
                                <div class="panel-dropdown-content right">
                                    <div class="qtyButtons">
                                        <label>Adultos</label>
                                        <input type="text" name="qtyInput" value="1">
                                    </div>
                                    <div class="qtyButtons">
                                        <label>Chicos</label>
                                        <input type="text" name="qtyInput" value="0">
                                    </div>
                                </div>
                            </div>

                            
                            </div>
                            <a href="cart-1.html" class=" add_top_30 btn_1 full-width purchase">Reservar</a>
                            
                            <div class="text-center"><small>No se carga dinero en esta etapa</small></div>
                        </div>
                        <ul class="share-buttons">
                            <li><a class="fb-share" href="#0"><i class="social_facebook"></i> Compartir</a></li>
                            
                            <li><a class="gplus-share" href="#0"><i class="social_googleplus"></i> Compartir</a></li>
                        </ul>
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
<script src="{{ asset('resources/js/service-public-pg.js') }}"></script>

<script>
var activity_start = "{{ App\Lib\Reservations::getCurrentYearActivityStart()->format('d/m/Y') }}";
var activity_end = "{{ App\Lib\Reservations::getCurrentYearActivityEnd()->format('d/m/Y') }}";
var app_url = "{{ config('app.url').'/' }}";
var serv_number = {{ $service->number }};
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