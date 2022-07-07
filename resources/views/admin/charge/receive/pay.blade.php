@extends('layouts.app')

@section('content')
    <div class='card'>
        <div class='card-header'>{{ __('Pagar - Conta a Receber') }}</div>
        <div class='card-body'>
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label">Cliente</label>
                    <h4>{{$model->customerName}}</h4>
                </div>
                <div class="col-md-6">
                    <label class="control-label">Descrição</label>
                    <h4>{{$model->title}}</h4>
                </div>
                <div class="col-md-6">
                    <label class="control-label">Valor</label>
                    <h4 style='text-decoration:underline;cursor:pointer'
                        onclick="$(this).parents('.card-body').find('.value').val($(this).data('value'))"
                        class='text-info' data-value="{{ $model->value - $model->pay }}">
                        <small>R$ </small>{{str()->numberBr($model->value - $model->pay)}}
                    </h4>
                </div>
                <div class="col-md-6">
                    <label class="control-label">Vencimento</label>
                    <h4>{{ str()->date($model->date) }}</h4>
                </div>
            </div>
            <hr/>
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
