@extends('entidade::layouts.master')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'>Editar Banco</div>
    </div>

    <div class='card-body'>
        {!! form_start($form)!!}
        <div class='row'>
            <div class='col-3'>{!! form_row($form->nome) !!}</div>
            <div class='col-3'>{!! form_row($form->banco_codigo) !!}</div>
            <div class='col-3'>{!! form_row($form->documento) !!}</div>
            <div class='col-3'>{!! form_row($form->ativo) !!}</div>
        </div>
        <div class='row'>
            <div class='col-6'>{!! form_row($form->email) !!}</div>
            <div class='col-6'>{!! form_row($form->telefone) !!}</div>
        </div>
        {!! form_row($form->btn) !!}
        {!! form_end($form, false)!!}
    </div>
</div>
@endsection
