@extends('cobranca::layouts.master')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'>Cadastro de cobrança</div>
    </div>

    <div class='card-body'>
        <div class='row'>
            <div class='col-md-6'>
                <label class='control-label'>Nome</label>
                <h4>{!! $obj->entidade?->nome ?: '-' !!}</h4>
            </div>
            <div class='col-md-6'>
                <label class='control-label'>Resumo</label>
                <h4>{{ $obj->descricao }}</h4>
            </div>
            <div class='col-md-6'>
                <label class='control-label'>Valor</label>
                <h4>R${!! $obj->valor_cobranca !!}</h4>
            </div>
            <div class='col-md-6'>
                <label class='control-label'>Vencimento</label>
                <h4>{!! str()->date($obj->data_vencimento) !!}</h4>
            </div>
        </div>

        @if($obj->observacao)
            <pre class='card card-body bg-light'>{!! $obj->observacao !!}</pre>
        @endif

        <hr />

        {!! form_start($form)!!}
        <div class='row'>
            <div class='col-md-4'>{!! form_row($form->valor_cobranca) !!}</div>
            <div class='col-md-4'>{!! form_row($form->conta_bancaria_id) !!}</div>
            <div class='col-md-4'>{!! form_row($form->forma_pagamento_id) !!}</div>
            <div class='col-md-6'>{!! form_row($form->valor_multa) !!}</div>
            <div class='col-md-6'>{!! form_row($form->valor_juros) !!}</div>
            <div class='col-md-6'>{!! form_row($form->valor_desconto) !!}</div>
            <div class='col-md-6'>{!! form_row($form->valor_total) !!}</div>
        </div>
        {!! form_end($form)!!}
    </div>
</div>
@endsection

@section('js')
<script>
$(function(){
    $('[name="valor_cobranca"], [name="valor_multa"], [name="valor_desconto"], [name="valor_juros"]').on('change', function(){
        const valorCobranca = parseFloat($('[name="valor_cobranca"]').val().replace('.', '').replace(',', '.'));
        let valorMulta = parseFloat($('[name="valor_multa"]').val().replace('.', '').replace(',', '.'));
        let valorDesconto = parseFloat($('[name="valor_desconto"]').val().replace('.', '').replace(',', '.'));
        let valorJuros = parseFloat($('[name="valor_juros"]').val().replace('.', '').replace(',', '.'));

        if(isNaN(valorMulta)){
            valorMulta = 0;
        }

        if(isNaN(valorDesconto)){
            valorDesconto = 0;
        }

        if(isNaN(valorJuros)){
            valorJuros = 0;
        }

        let calcular = valorCobranca;

        calcular = calcular + valorMulta + valorJuros - valorDesconto;

        const inputSubtotal = $('[name="valor_total"]');
        inputSubtotal.val(calcular)
    }).on('keyup', function(){
        $(this).trigger('change');
    });
})
</script>
@endsection
