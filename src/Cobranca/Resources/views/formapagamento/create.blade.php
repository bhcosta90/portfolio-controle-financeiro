@extends('cobranca::layouts.master')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'>Cadastro de forma de pagamento</div>
    </div>

    <div class='card-body'>
        {!! form($form)!!}
    </div>
</div>
@endsection
