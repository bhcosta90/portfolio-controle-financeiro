@extends('layouts.app')
@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'><h4>Pagar cobrança</h4></div>
    </div>
    <div class='card-body'>
        <div class='row'>
            <div class='col-md-4'>
                <label class='control-label'>Nome</label>
                <h4>{!! $obj->customer_name !!}</h4>
            </div>
            <div class='col-md-4'>
                <label class='control-label'>Valor</label>
                <h4>R${!! Str::numberEnToBr($obj->value) !!}</h4>
            </div>
            <div class='col-md-4'>
                <label class='control-label'>Vencimento</label>
                <h4>{!! Str::date($obj->due_date) !!}</h4>
            </div>
        </div>
        {!! form($form) !!}
    </div>
</div>
@endsection
