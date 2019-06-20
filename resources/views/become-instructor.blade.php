@extends('layouts.main')

@section('title', 'Ser instructor')

@section('content')
        

        
        <div class="container">
            <div class="main_title_2">
                <span><em></em></span>
                <h2></h2>
                <p></p>
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
              <div class="container clearfix">
                <div class="col-lg-5 col-md-6 float-right wow" data-wow-offset="250">
                    <div class="block-reveal">
                        <div class="block-vertical"></div>
                        <div class="box_1">
                            {{--<a href="{{ route('instructor.register') }}" class="btn_1 rounded">Registrarse</a>--}}
                            <a href="{{ route('instructor.login') }}" class="btn_1 rounded">Registrarse</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>      
  
        <!-- /container -->
        
       

        <!-- /bg_color_1 -->

        
          
@endsection



@section('custom-js')


@endsection