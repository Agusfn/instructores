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
    .mm-slideout {
        border-bottom: 1px solid #ededed!important;
    background-color: #fff !important;
    
    color: black !important;
}
   .mm-slideout p{
    
    color: black !important;
}
 .mm-slideout   ul > li span > a {
    color: #444 !important;   
}

.mm-slideout   ul > li span > a:hover {
    color: #fc5b62 !important;   
}

.hamburger-inner, .hamburger-inner::after, .hamburger-inner::before {
    width: 30px;
    height: 4px;
    background-color: #333 !important;
    border-radius: 0;
    position: absolute;
    transition-property: transform;
    transition-duration: .15s;
    transition-timing-function: ease;
}

</style>
@extends('layouts.main')


@section('title', 'Ser instructor')

@section('content')
        

       <br><br>
        <div class="container margin_60_35"></div>
          


              

                
                <div class="container" >
                    
                     
                       
                        <div class="main_title_3">
                         
                         <h2 >Sos instructor de ski o snowboard?</h2>
                         <span><em></em></span><span><em></em></span><span><em></em></span><span><em></em></span>
                        <strong>Entérate de los beneficios de trabajar mediante nuestra plataforma:</p>
                        <p>• Comisiones accesibles.</p>
                        <p>• Clientes desde la comodidad de tu hogar.</p>
                        <p>• Organizá tu agenda de instructor con anticipación.</p>
                        <p>• Creá y negociá tus propias promociones.</p>

                        <div class="container">
                            <p class="pull-center">Llevá tus clases a otro nivel, no pierdas más tiempo.</p></div>
                            <a  href="{{ route('instructor.login') }}" class="btn_1 rounded" >REGISTRARME</a>
                        </div>
                  
                 

                        


                </div>  

                     
                     

               


                    

           
        


        
            
 
@endsection



@section('custom-js')


@endsection