@extends('layouts.main')

@section('title', 'Inicio')

@section('custom-css')
<style type="text/css">
    .discipline-select {
        height: 50px
    }
    .discipline-select > .nice-select {
        margin: 0;
        height: 50px !important;
        border: 0 !important;
        border-right: 1px solid #d2d8dd !important;
    }
    .discipline-select > .nice-select > .list {
        height: auto;
    }

    .daterangepicker td.disabled {
        cursor: default;
        /*text-decoration: none;*/
    }
</style>

@endsection


@section('content')
        <section class="hero_single version_2">
            <div class="wrapper">
                <div class="container">
                    <h3>Clases de Ski & Snowboard en Cerro Catedral</h3>
                    <p><strong>Viví la emoción de la montaña. Reserva tus clases ahora.</strong></p>
                    <form action="{{ route('search') }}" method="GET" id="search-form">
                        <div class="row no-gutters custom-search-input-2">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="custom-select-form discipline-select">
                                        <select class="wide add_bottom_15" name="discipline" autocomplete="off">
                                            <option value="ski">Ski</option>
                                            <option value="snowboard">Snowboard</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="date" placeholder="Fecha" autocomplete="off">
                                    <i class="icon_calendar"></i>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="panel-dropdown">
                                    <a href="#">Personas <span class="qtyTotal">1</span></a>
                                    <div class="panel-dropdown-content">
                                        <!-- Quantity Buttons -->
                                        <div class="qtyButtons">
                                            <label>Adultos</label>
                                            <input type="text" class="qtyInput" name="qty_adults" value="1" data-max="6" autocomplete="off">
                                        </div>
                                        <div class="qtyButtons">
                                            <label>Chicos</label>
                                            <input type="text" class="qtyInput" name="qty_kids" value="0" data-max="6" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <button type="button" class="btn_search" id="search-btn">Buscar</button>
                            </div>
                        </div>
                        <input type="hidden" name="sort" value="default">
                        <!-- /row -->
                    </form>
                     <div class="container"></br></br></br>
                      <div class="col-lg-12">

                        <span class="pull-left">
                            <strong >#Ski&Snowboard</strong></n>
                            <strong >#Bariloche2019</strong>
                            <strong >#CerroCatedral</strong>
                        </span>
                    </div>
                </div>
            </div>
        </section>
        <!-- /hero_single -->



                <div class="container" id="bannerpagos">

                    <div class="row" id="banner" >

                    <div class="row">
                    <a  id="bannerbox1">
                     <img src="https://imgmp.mlstatic.com/org-img/banners/ar/medios/785X40.jpg" title="MercadoPago - Medios de pago" alt="MercadoPago - Medios de pago" width="100%" height="40"/>
                    </a>
                    </div>

                    </div> 

 
                    <div class="row">

                    
                    <div class="col-lg-4">
                    <div class="box_feat" id="tittlebanbox">
                        <h6>Todos los metodos de pago.</h6> <p>Efectivo, transferencias, tarjetas de débito y crédito.</p>
                    </div>
                    
                    </div>


                    <div class="col-lg-5">
                    <div class="box_feat" id="tittlebanbox">
                        <h6>Cuotas sin intéres.</h6>
                        <p>Con tarjetas de crédito Mastercard emitidas por Banco Patagonia y Mercado Pago.<a href="https://www.mercadopago.com.ar/cuotas" target="_blank"> Ver condiciones.</a></p>

                    </div>
                    </div>

                    <div class="col-lg-3">
                    <div class="box_feat" id="tittlebanbox">
                        
                        <a href="https://www.mercadopago.com.ar/ayuda/terminos-y-politicas_194" target="_blank">Ver términos y políticas generales de Mercadopago.</a>
                    </div>
                    </div>


                    </div>
                </div>

     

                
             

        <div class="container-fluid margin_80_55">

            <div class="main_title_2" id="titulo2">
                <span><em></em></span>
                <h2>Por qué elegirnos</h2>
                <p>Brindamos confianza</p>
            </div>

            <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6" id="box1" >
                    <a class="box_feat">
                        <i class="pe-7s-global"></i>
                        <h3>Instructores internacionales</h3>
                        <p>Todos nuestros instructores tienen experiencia en diferentes centros invernales de todo el globo.</p>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6" id="box2">
                    <a class="box_feat">
                        <i class="pe-7s-medal"></i>
                        <h3>Certificación profesional</h3>
                        <p>Nuestros instructores de Ski y Snowboard cuentan con certificación emitida por instituciones oficiales.</p>
                    </a>
                </div>

                    <div class="col-lg-4 col-md-6" id="box3">
                    <a class="box_feat" id="contben">
                        <i class="pe-7s-piggy"></i>
                        <h3>Ahorrá</h3>
                        <p>Accede a a los mejores precios por clase, al nivel que necesitas.</p>
                    </a>
                </div>

                    <div class="col-lg-4 col-md-6" id="box4">
                    <a class="box_feat" id="contben">
                        <i class="pe-7s-headphones"></i>
                        <h3>Soporte online</h3>
                        <p>Te brindamos servicio de soporte via chat 24/7.</p>
                    </a>
                </div>

                    <div class="col-lg-8 col-md-12" id="box5">
                    <a class="box_feat">
                        <i class="pe-7s-chat"></i>
                        <h3>Sistema de reputación</h3>
                        <p>Nos manejamos por un sistema de reputación para cada instructor, basado en  el nivel académico del mismo, en la responsabilidad al momento de ejercer su profesión y en los comentarios y sugerencias de nuestros clientes.</p>
                    </a>
                </div>

                </div>
            
              
            </div>
            
            <!--/row-->
        </div>  

        
        <div class="container-fluid" id="barratel">

               

                    <div class="row">


                        <div class="col-lg-3"></div><div class="whatsapp"></div> 

                     


                      <div class="box_feat" id="tittlebanbox">
                         
                       <div class="row" id="bannerbox1">

                        <div class="col-lg-5" class="whatsapp"> 
                        <h5>Whatsapp </h5>
                        <h3>+54 9294 4305811</h3>
                       </div>
                       <div class="col-lg-6"><strong><p>Ante cualquier duda comunicate con nosotros, estamos para asistirte.</p></strong></div>
                       
                    
                     </div>
                                <!--
                                <div class="col-lg-6 col-md-12 offset-0 float-left">
                                      <div class="rev-content">
                                        <div class="rating">
                                            <i class="icon_star voted"></i><i class="icon_star voted"></i><i class="icon_star voted"></i><i class="icon_star voted"></i><i class="icon_star voted"></i>
                                        </div>
                                        <div class="rev-info">
                                            Juan Lucas – Julio 21, 2018:
                                        </div>
                                        <div class="rev-text">
                                            <p>
                                                Excelente atención, muy conformes con todo. Sin dudas volvería a contratar por aquí.
                                            </p>
                                        </div>
                                    </div>
            
                                   </div> 
                                   --> 



                     </div> 

                    
        </div>
         <div class="container" id="testify">
            
 

        
        </div>
        <!-- /container -->
        
             
                
            
        </div>
        <!-- /container -->

        <!-- /bg_color_1 -->

        <div class="call_section" >
            <div class="container clearfix">
                <div class="col-lg-5 col-md-6 float-right wow" data-wow-offset="250">
                    <div class="block-reveal" id="sosints">
                        
                        <div class="box_1" >
                            <h3>¿Sos instructor?</h3>
                            <p>Deberías trabajar con nosotros!</p>
                            <a href="{{ route('become-instructor') }}" class="btn_1 rounded">Saber más</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/call_section-->
@endsection



@section('custom-js')

    <!-- DATEPICKER  -->
    <script>
    $(function() {
        'use strict';
        $('input[name="date"]').daterangepicker({
            singleDatePicker: true,
            autoUpdateInput: false,
            minDate: "{{ $activityStartDate->isPast() ? (new Carbon\Carbon())->format('d/m/Y') : $activityStartDate->format('d/m/Y') }}",
            maxDate: "{{ $activityEndDate->format('d/m/Y') }}",
            locale: {
                format: 'DD/MM/YYYY',
                cancelLabel: 'Cancelar'
            }
        });

        $('input[name="date"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY'));
        });
        $('input[name="date"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $("#search-btn").click(function() {

            var date = $("input[name=date]").val();
            var adults =  parseInt($("input[name=qty_adults]").val());
            var kids = parseInt($("input[name=qty_kids]").val());

            if(!moment(date, "DD/MM/YYYY").isValid()) {
                alert("Selecciona una fecha");
                return;
            }

            if(adults + kids < 1) {
                alert("Debe haber al menos una persona.");
                return;
            }

            if(adults + kids > 6) {
                alert("Pueden haber 6 personas como máximo.");
                return;
            }

            $("#search-form").submit();
        });


    });
    </script>

    <!-- INPUT QUANTITY  -->
    <script src="{{ asset('resources/js/input_qty.js') }}"></script>
    
    
    
@endsection