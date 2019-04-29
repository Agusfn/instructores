<div class="col-3">

	<ul class="nav nav-pills flex-column h5">
		<li class="nav-item">
			<a class="nav-link {{ request()->is('*/cuenta') ? 'active' : '' }}" href="{{ url('panel/cuenta') }}">Mi cuenta</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{ request()->is('*/reservas') ? 'active' : '' }}" href="{{ url('panel/reservas') }}">Mis reservas</a>
		</li>			
	</ul>

</div>