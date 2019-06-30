@section('custom-css')
<style type="text/css">
    .sr {background-color: whitesmoke;}.
    #page {background-color: whitesmoke;}
    .mm-slideout {
        background-color: whitesmoke!important;
        color: black !important;
    }
    .margin_80_55 {
        background-color: whitesmoke !important;

    }
    
    #registbotton{
        margin-top: 0%;
        margin-bottom: 0%;
        
       
    }

    #ofertas {
        display: none;
    }

    .main_title_3 span em {
    width: 60px;
    height: 2px;
    background-color: #0054a6!important;
    display: block;
}
</style>
@extends('layouts.main')


@section('title', 'Ser instructor')

@section('content')
        

          
          <div class="container" id="contsosinsts" >
              


                <div class="container" >
                    
                     
                       
                         <div class="main_title_3">
                         
                         <h2 >Sos instructor de ski o snowboard?</h2>
                         <span><em></em></span><span><em></em></span><span><em></em></span><span><em></em></span>
                        <strong>Entérate de los beneficios de trabajar mediante nuestra plataforma:</p>
                        <p>• Comisiones accesibles.</p>
                        <p>• Clientes desde la comodidad de tu hogar.</p>
                        <p>• Organizá tu agenda de instructor con anticipación.</p>
                        <p>• Creá y negociá tus propias promociones.</p>

                        <div class="col-lg-6 pull-right"><p class="">Llevá tus clases a otro nivel, no pierdas más tiempo.</p></div>

                        </div>




                </div>      
                     
                        <div class="row"><div class="col-lg-6 " id="registbotton"><a href="{{ route('instructor.login') }}" class="btn_1 rounded pull-right" >REGISTRARME</a></div></div>

               


                    

           
                
          </div>


        
            
 
@endsection



@section('custom-js')


@endsection