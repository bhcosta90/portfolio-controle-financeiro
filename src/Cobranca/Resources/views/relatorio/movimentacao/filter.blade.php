@extends('cobranca::layouts.relatorio')

@section('content')
     <table style='margin-bottom: 25px;'>
        <tr>
            <td class='titulo'>Relatório de movimentação bancária</td>
            <td class='empresa' style='text-align:right'>{!! tenant('name') !!}</td>
        </tr>

        <tr>
            <td class='apuracao'>Apuração: {{$dataInicio}} à {{$dataFinal}}</td>
            <td class='data' style='text-align:right'>{{ $dataHumano }}</td>
        </tr>
    </table>

    <h2 style='text-align:right; font-weight: normal' class='{{ $total < 0 ? 'text-danger' : 'text-success' }}'><small>Saldo: R$</small>{{ str()->numberEnToBr($total) }}</h2>
    <table class='list'>
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Movimento</th>
                <th>Conta</th>
                <th>Documento</th>
                <th>Valor</th>
                <th>Saldo</th>
            </tr>
        </thead>
        @foreach($data as $rs)
        <tr class={{$rs->tipo_cobranca == \Modules\Cobranca\Models\Cobranca::$TIPO_CREDITO ? 'text-success' : 'text-danger'}}>
            <td>{{ str()->date($rs->dt_created_at) }}</td>
            <td>{{ $rs->descricao ?: '-' }}</td>
            <td>{!! $rs->formatacao_movimento !!}</td>
            <td>{!! $rs->conta_bancaria?->entidade->nome ?: $rs->getTipoMovimentoFormatarAttribute(-1) !!}</td>
            <td>{{ $rs->forma_pagamento->nome }}</td>
            <td>{{ str()->numberEnToBr($rs->valor_total) }}</td>
            <td>{{ str()->numberEnToBr($rs->saldo_atual) }}</td>
        </tr>
        @endforeach
    </table>

    <hr style='margin-top: 20px' />
    <h2 style='text-align:right; font-weight: normal' class='{{ $total < 0 ? 'text-danger' : 'text-success' }}'><small>Saldo: R$</small>{{ str()->numberEnToBr($total) }}</h2>
@endsection
