	<header class="header menu_fixed">
		<div id="preloader"><div data-loader="circle-side"></div></div><!-- /Page Preload -->
		<div id="logo">
			<a href="{{ route('home') }}">
				<img src="" width="150" height="36" data-retina="true" alt="" class="logo_normal">
				<img src="" width="150" height="36" data-retina="true" alt="" class="logo_sticky">
			</a>
		</div>
		
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


	</header>