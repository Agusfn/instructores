	<header class="header menu_fixed">
		<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129656474-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-129656474-1');
</script>

		<div id="preloader"><div data-loader="circle-side"></div></div><!-- /Page Preload -->
		

		<div class="col-lg-1 col-md-6"><div id="logo">
            <div class="container">
			<a href="{{ route('home') }}"><span><h6>INSTRUCTORES</h6><p>SKI&SNOWBOARD</p></span>
				<!--
				<img src="resources/img/logo.png" width="150" height="36" data-retina="true" alt="" class="logo_normal">
				<img src="resources/img/logosticky.png" width="150" height="36" data-retina="true" alt="" class="logo_sticky">
				-->
			</a>
			</div>
		</div> 
		</div>
        

        <!--BARRA DE OFERTAS-->
         <div class="container" >  
                <div class="col-lg-6 col-md-6 float-left" id="barraofertas" >                       
                            <a id="ofertas" href="{{ route('become-instructor') }}" class="btn_1 rounded">¡Ofertas disponibles! Finalizan en: 05:40:03 Horas.</a>
                </div>
                 </div>
        <!--/BARRA DE OFERTAS-->        
            
		
		<a href="#menu" class="btn_mobile">
			<div class="hamburger hamburger--spin" id="hamburger">
				<div class="hamburger-box">
					<div class="hamburger-inner"></div>
				</div>
			</div>
		</a>
		<nav id="menu" class="main-menu">
			<ul>

				
				<li><span><a href="{{ route('faq') }}">PREGUNTAS FRECUENTES</a></span>
				</li>
				
				@guest
				<li>
					<span><a href="{{ route('user.login') }}">INGRESAR</a></span>
				</li>


				@else
					@user
					<li>
						<span><a href="{{ route('user.reservations') }}">MIS RESERVAS</a></span>
					</li>
					<li>
						<span><a href="{{ route('user.account') }}">MI CUENTA</a></span>
					</li>					
					<li>
						<span><a href="javascript:void(0);" onclick="event.preventDefault();document.getElementById('logout-form').submit();">CERRAR SESION</a></span>
					</li>
                    <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
					@else
					<li>
						<span><a href="{{ route('instructor.account') }}">PANEL INSTRUCTOR</a></span>
					</li>				
					<li>
						<span><a href="javascript:void(0);" onclick="event.preventDefault();document.getElementById('logout-form').submit();">CERRAR SESION</a></span>
					</li>
                    <form id="logout-form" action="{{ route('instructor.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>					
                    @enduser
				@endguest
				
			</ul>
		</nav>

       <script src="//code.jivosite.com/widget.js" jv-id="HDITS1kFVy" async></script>

	</header>