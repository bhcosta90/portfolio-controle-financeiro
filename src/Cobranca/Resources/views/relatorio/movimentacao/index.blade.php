@extends('cobranca::layouts.master')

@section('content')
    <div class='card'>
        <div class='card-header'>
            <div class='float-start'>Filtrar relatório</div>
            <div class=float-end><a class='btn btn-outline-info btn-sm' href="{{ route('cobranca.frequencia.create', ['tenant' => tenant()]) }}">Adicionar</a></div>
        </div>
        <div class='card-body'>
            {!! form_start($form) !!}
            <div class='row'>
                <div class='col-md-6'>{!! form_row($form->data_inicio) !!}</div>
                <div class='col-md-6'>{!! form_row($form->data_final) !!}</div>
            </div>
            {!! form_end($form) !!}
        </div>
    </div>
@endsection
