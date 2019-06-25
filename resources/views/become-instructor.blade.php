@section('custom-css')
<style type="text/css">
    .
    .mm-slideout {
        background-color: disabled !important;
    }
    .margin_80_55 {
        background-color: whitesmoke !important;

    }
    #registbotton{
        margin-top: 16%;
        margin-bottom: 5%;
       
    }

    #ofertas {
        display: none;
    }
</style>
@extends('layouts.main')


@section('title', 'Ser instructor')

@section('content')
        

          <div class="container" id="contsosinsts" >
            

                <div class="container margin_80_55  ">
                    
                     
                        <h4>Sos instructor de ski o snowboard?</h4>
                        <p>Llevá tus clases a otro nivel, no pierdas más tiempo.</p>
                        <strong>Entérate de los beneficios de trabajar mediante nuestra plataforma:</p>
                        <h6>• Comisiones accesibles.</h6>
                        <h6>• Clientes desde la comodidad de tu hogar.</h6>
                        <h6>• Organizá tu agenda de instructor con anticipación.</h6>
                        <h6>• Creá y negociá tus propias promociones.</h6>
                        
                  

               


                    

                <div class="row" id="registbotton">
                    <div class="col-lg-10"><a href="{{ route('instructor.login') }}" class="btn_1 rounded pull-right" >REGISTRARME</a></div>

                </div>
                
          </div>


        
            
 
@endsection



@section('custom-js')


@endsection