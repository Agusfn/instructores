@extends('emails.layout')


@section('content')

<p>
	Se ha solicitado una extracción de saldo de instructor por el monto de {{ round($collection->amount, 2) }} ARS, por el instructor {{ $instructor->name." ".$instructor->surname }}.<br/>
	Revisa las extracciones pendientes en el <a href="{{ route('admin.instructor-collections.list') }}">panel de admin</a>.
</p>

@endsection