<div class="col-md-3">

	<ul class="nav nav-pills flex-column h5">
		<li class="nav-item">
			<a class="nav-link {{ request()->is('*/cuenta') ? 'active' : '' }}" href="{{ url('instructor/panel/cuenta') }}">Mi cuenta</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{ request()->is('*/servicio') ? 'active' : '' }}" href="{{ url('instructor/panel/servicio') }}">Mi servicio</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{ request()->is('*/reservas') ? 'active' : '' }}" href="{{ url('instructor/panel/reservas') }}">Mis reservas</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{ request()->is('*/saldo') ? 'active' : '' }}" href="{{ url('instructor/panel/saldo') }}">Mi saldo</a>
		</li>			
	</ul>

</div>