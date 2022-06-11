@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>{{ __('Pagar Cobrança') }}</div>
    <div class='card-body'>
        <div class="row">
            <div class="col-md-6">
                <label class="control-label">Nome</label>
                <h4>{{$data->relationship->value}}</h4>
            </div>
            <div class="col-md-6">
                <label class="control-label">Resumo</label>
                <h4>{{$data->title}}</h4>
            </div>
            <div class="col-md-6">
                <label class="control-label">Valor</label>
                <h4 style='text-decoration:underline;cursor:pointer' onclick="$(this).parents('.card-body').find('.value').val($(this).data('value'))" class='text-info' data-value="{{ $data->value - $data->payValue }}">
                    <small>R$ </small>{{str()->numberEnToBr($data->value - $data->payValue)}}
                </h4>
            </div>
            <div class="col-md-6">
                <label class="control-label">Vencimento</label>
                <h4>{{ str()->date($data->date) }}</h4>
            </div>
        </div>
        <hr />
        {!! form_start($form) !!}
        <div class='row'>
            <div class='col-md-4'>{!! form_row($form->value_charge) !!}</div>
            <div class='col-md-4'>{!! form_row($form->value_pay) !!}</div>
            <div class='col-md-4'>{!! form_row($form->bank_id) !!}</div>
        </div>
        {!! form_end($form) !!}
    </div>
</div>
@endsection
