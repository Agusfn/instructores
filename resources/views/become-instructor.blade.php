@extends('layouts.main')


@section('content')
        

        
        <div class="container margin_80_55">
            <div class="main_title_2">
                <span><em></em></span>
                <h2>Por qué elegirnos</h2>
                <p>Brindamos confianza</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <a class="box_feat" href="#0">
                        <i class="pe-7s-medal"></i>
                        <h3>Instructores internacionales</h3>
                        <p></p>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a class="box_feat" href="#0">
                        <i class="pe-7s-help2"></i>
                        <h3>Certificación profesional</h3>
                        <p> </p>
                    </a>
                </div>
                    <div class="col-lg-4 col-md-6">
                    <a class="box_feat" href="#0">
                        <i class="pe-7s-chat"></i>
                        <h3>Sistema de reputación</h3>
                        <p> </p>
                    </a>
                </div>
            
                <div class="col-lg-4 col-md-6">
                    <a class="box_feat" href="#0">
                        <i class="pe-7s-headphones"></i>
                        <h3>Soporte por chat</h3>
                        <p> </p>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a class="box_feat" href="#0">
                        <i class="pe-7s-credit"></i>
                        <h3>Pagos seguros</h3>
                        <p></p>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <a class="box_feat" href="#0">
                        <i class="pe-7s-culture"></i>
                        <h3>Ahorrá</h3>
                        <p></p>
                    </a>
                </div>
            </div>
            <!--/row-->
        </div>      
                

        
                
            <hr class="large">
        </div>
        <!-- /container -->
        
        <div class="container-fluid margin_30_95 pl-lg-5 pr-lg-5">
            <section class="add_bottom_45">
                <div class="main_title_3">
                
                    <h2>Testimonios</h2>
                    <p></p>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <a href="#" class="grid_item">
                            <figure>
                                <div class="score"><strong>8.9</strong></div>
                                <img src="{{ asset('resources/img/hotel_1.jpg') }}" class="img-fluid" alt="">
                                <div class="info">
                                    <div class="cat_star"><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i></div>
                                    <h3></h3>
                                </div>
                            </figure>
                        </a>
                    </div>
                
                
                    <!-- /grid_item -->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <a href="#" class="grid_item">
                            <figure>
                                <div class="score"><strong>7.0</strong></div>
                                <img src="{{ asset('resources/img/hotel_3.jpg') }}" class="img-fluid" alt="">
                                <div class="info">
                                    <div class="cat_star"><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i></div>
                                    <h3></h3>
                                </div>
                            </figure>
                        </a>
                    </div>
                    <!-- /grid_item -->
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <a href="#" class="grid_item">
                            <figure>
                                <div class="score"><strong>8.9</strong></div>
                                <img src="{{ asset('resources/img/hotel_4.jpg') }}" class="img-fluid" alt="">
                                <div class="info">
                                    <div class="cat_star"><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i></div>
                                    <h3></h3>
                                </div>
                            </figure>
                        </a>
                    </div>
                    <!-- /grid_item -->
                </div>
                <!-- /row -->
                <a href="#"><strong> <i class="arrow_carrot-right"></i></strong></a>
            </section>
            
                    
                
            
        </div>
        <!-- /container -->

        <!-- /bg_color_1 -->

        <div class="call_section">
            <div class="container clearfix">
                <div class="col-lg-5 col-md-6 float-right wow" data-wow-offset="250">
                    <div class="block-reveal">
                        <div class="block-vertical"></div>
                        <div class="box_1">
                            <a href="{{ route('instructor.register') }}" class="btn_1 rounded">Registrarse</a>
                            <a href="{{ route('instructor.login') }}" class="btn_1 rounded">Iniciar sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/call_section-->
@endsection



@section('custom-js')


@endsection