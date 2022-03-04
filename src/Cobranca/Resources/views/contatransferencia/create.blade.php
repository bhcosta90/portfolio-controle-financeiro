@extends('cobranca::layouts.master')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'>Transferir entre contas</div>
    </div>

    <div class='card-body'>
        {!! form_start($form)!!}
        <div class='row'>
            <div class='col-md-4'>{!! form_row($form->conta_origem) !!}</div>
            <div class='col-md-4'>{!! form_row($form->conta_destino) !!}</div>
            <div class='col-md-4'>{!! form_row($form->valor_transferencia) !!}</div>
        </div>

        {!! form_end($form)!!}
    </div>
</div>
@endsection
