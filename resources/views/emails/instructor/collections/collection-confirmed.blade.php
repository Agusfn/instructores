@extends('emails.layout')


@section('content')

<p>
	{{ $instructor->name }}, tu retiro de dinero de {{ $collection->amount }} ARS solicitado el {{ $collection->created_at->format('d/m/Y') }} se ha completado. Tu saldo ha sido retirado a
	@if($collection->isToBank())
	tu cuenta bancaria CBU {{ $instructor->bankAccount->cbu }}.
	@else
	tu cuenta de MercadoPago {{ $instructor->mpAccount->email }}.
	@endif
</p>

@endsection