@extends('emails.layout')


@section('content')

<p>{{ $instructor->name }}, se ha modificado la cuenta bancaria hacia donde se retiran los fondos de tu cuenta de saldo de instructor.<br>
La nueva cuenta es:<br/>
CBU: {{ $instructor->bankAccount->cbu }}<br/>
Titular: {{ $instructor->bankAccount->holder_name }}<br/>
Documento: {{ $instructor->bankAccount->document_number }}<br/>
CUIL/CUIT: {{ $instructor->bankAccount->cuil_cuit }}<br/>
<br/>
Se podrán realizar extracciones de saldo luego de 3 días de este cambio.
</p>

@endsection