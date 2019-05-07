@extends('layouts.main')


@section('content')

        <section class="hero_in hotels_detail">
            <div class="wrapper">
                <div class="container">
                    <h1 class="fadeInUp"><span></span>Perfil</h1>
                </div>
                <span class="magnific-gallery">
                    <a href="{{ asset('resources/img/gallery/hotel_list_1.jpg') }}" class="btn_photos" title="Photo title" data-effect="mfp-zoom-in">Ver fotos</a>
                    <a href="{{ asset('resources/img/gallery/hotel_list_2.jpg') }}" title="Photo title" data-effect="mfp-zoom-in"></a>
                    <a href="{{ asset('resources/img/gallery/hotel_list_3.jpg') }}" title="Photo title" data-effect="mfp-zoom-in"></a>
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
                            <p>Per consequat adolescens ex, cu nibh commune <strong>temporibus vim</strong>, ad sumo viris eloquentiam sed. Mea appareat omittantur eloquentiam ad, nam ei quas oportere democritum. Prima causae admodum id est, ei timeam inimicus sed. Sit an meis aliquam, cetero inermis vel ut. An sit illum euismod facilisis, tamquam vulputate pertinacia eum at.</p>
                            <p>Cum et probo menandri. Officiis consulatu pro et, ne sea sale invidunt, sed ut sint <strong>blandit</strong> efficiendi. Atomorum explicari eu qui, est enim quaerendum te. Quo harum viris id. Per ne quando dolore evertitur, pro ad cibo commune.</p>
                            <div class="row">
                                <div class="col-lg-6">
                                    <ul class="bullets">
                                        <li>Dolorem mediocritatem</li>
                                        <li>Mea appareat</li>
                                        <li>Prima causae</li>
                                        <li>Singulis indoctum</li>
                                    </ul>
                                </div>
                                <div class="col-lg-6">
                                    <ul class="bullets">
                                        <li>Timeam inimicus</li>
                                        <li>Oportere democritum</li>
                                        <li>Cetero inermis</li>
                                        <li>Pertinacia eum</li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /row -->
                            <hr>
                            <h3>Cuenta de Instagram</h3>
                            <div id="instagram-feed-hotel" class="clearfix"></div>
                            <hr>
                            
                        
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
                                <span>$3.000<small>/persona</small></span>
                                <div class="score"><span>Excelente<em>350 votos</em></span><strong>9.6</strong></div>
                            </div>

                            <div class="form-group">
                                <input class="form-control" type="text" name="dates" placeholder="Fecha..">
                                <i class="icon_calendar"></i>
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

    <!-- Map -->
    <!--script src="http://maps.googleapis.com/maps/api/js"></script-->
    <script src="{{ asset('resources/js/map_single_hotel.js') }}"></script>
    <script src="{{ asset('resources/js/infobox.js') }}"></script>
    
    <!-- DATEPICKER  -->
    <script>
    $(function() {
      $('input[name="dates"]').daterangepicker({
          autoUpdateInput: false,
          opens: 'left',
          locale: {
              cancelLabel: 'Clear'
          }
      });
      $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
          $(this).val(picker.startDate.format('MM-DD-YY') + ' > ' + picker.endDate.format('MM-DD-YY'));
      });
      $('input[name="dates"]').on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
      });
    });
    </script>
    
    <!-- INPUT QUANTITY  -->
    <script src="{{ asset('resources/js/input_qty.js') }}"></script>
    
    <!-- INSTAGRAM FEED  -->
    <script>
    $(window).on('load', function(){
            "use strict";
            $.instagramFeed({
                'username': 'hotelwailea',
                'container': "#instagram-feed-hotel",
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

@endsection