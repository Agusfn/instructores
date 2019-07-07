
	<header class="header menu_fixed ">
		<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129656474-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-129656474-1');
</script>
        <div class="col-lg-12">
		<div id="preloader"><div data-loader="circle-side"></div></div><!-- /Page Preload -->
		
         
		 <div id="logo">       
			<a  href="{{ route('home') }}"><h6>INSTRUCTORES</h6><p>SKI&SNOWBOARD</p>
				<!--
				<img src="resources/img/logo.png" width="150" height="36" data-retina="true" alt="" class="logo_normal">
				<img src="resources/img/logosticky.png" width="150" height="36" data-retina="true" alt="" class="logo_sticky">
				-->
			</a>
		
		</div> 

		
        

        <!--BARRA DE OFERTAS
         <div class="container" >  
                <div class="col-lg-6 col-md-6 float-left" id="barraofertas" >                       
                            <a id="ofertas" href="{{ route('become-instructor') }}" class="btn_1 rounded">¡Ofertas disponibles! Finalizan en: 05:40:03 Horas.</a>
                </div>
                 </div>
        BARRA DE OFERTAS-->        
            
		
		<a href="#menu" class="btn_mobile">
			<div class="hamburger hamburger--spin" id="hamburger">
				<div class="hamburger-box">
					<div class="hamburger-inner"></div>
				</div>
			</div>
		</a>

		<nav id="menu" class="main-menu">
			<ul>

				<li>
					<span><a href="{{ route('faq') }}">PREGUNTAS FRECUENTES</a></span>

				</li>
				
				@guest
				<li>
					<span><a href="{{ route('instructor.login') }}"><i class="pe-7s-medal" style="margin-right: 5px; font-size: 18px"></i>SOY INSTRUCTOR</a></span>
				</li>
				<li>
					<span><a href="{{ route('user.login') }}"><i class="pe-7s-user" style="margin-right: 5px; font-size: 18px"></i>INGRESAR</a></span>
				</li>


				@else
					@user
					
					<li>
						<span><a href="{{ route('user.account') }}"><i class="pe-7s-note2" style="margin-right: 5px; font-size: 18px"></i>MI CUENTA</a></span>
					</li>					
					<li>
						<span><a href="javascript:void(0);" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="pe-7s-power" style="margin-right: 5px; font-size: 18px"></i>CERRAR SESION</a></span>
					</li>
                    <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
					@else
					<li>
						<span><a href="{{ route('instructor.account') }}"><i class="pe-7s-note2" style="margin-right: 5px; font-size: 18px"></i>PANEL INSTRUCTOR</a></span>
					</li>				
					<li>
						<span><a href="javascript:void(0);" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="pe-7s-power" style="margin-right: 5px; font-size: 18px"></i>CERRAR SESION</a></span>
					</li>
                    <form id="logout-form" action="{{ route('instructor.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>					
                    @enduser
				@endguest
				
			</ul>
		</nav>
		</div>

       

	</header>