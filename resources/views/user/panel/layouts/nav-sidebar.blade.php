	<ul class="nav nav-pills flex-column h5">
		<li class="nav-item">
			<a class="nav-link {{ request()->is('*/cuenta*') ? 'active' : '' }}" href="{{ url('panel/cuenta') }}">Datos Personales</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{ request()->is('*/reservas*') ? 'active' : '' }}" href="{{ url('panel/reservas') }}">Reservas</a>
		</li>			
	</ul>