@extends('emails.layout')


@section('content')

<p>
	{{ $instructor->name }}, se ha modificado desde tu panel de instructor la cuenta {{ $accountType == "bank" ? "bancaria" : "de MercadoPago" }} hacia donde se retiran los fondos de tu cuenta de saldo de instructor.<br>
	La nueva cuenta es:<br/>
	@if($accountType == "bank")
	CBU: {{ $instructor->bankAccount->cbu }}<br/>
	Titular: {{ $instructor->bankAccount->holder_name }}<br/>
	Documento: {{ $instructor->bankAccount->document_number }}<br/>
	CUIL/CUIT: {{ $instructor->bankAccount->cuil_cuit }}<br/>
	@else
	{{ $instructor->mpAccount->email }}
	@endif
	<br/>
	Se podrán realizar extracciones de saldo luego de {{ App\Lib\InstructorCollections\WithdrawableAccount::LOCK_TIME_DAYS }} día/s de este cambio.
</p>

@endsection