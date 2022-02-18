@extends('cobranca::layouts.master')

@section('content')
    <div class='card'>
        <div class='card-header'>
            <div class='float-start'>Relatório de forma de pagamento</div>
            <div class=float-end><a class='btn btn-outline-info btn-sm' href="{{ route('cobranca.formapagamento.create', ['tenant' => tenant()]) }}">Adicionar</a></div>
        </div>
        {!! $table->render() !!}
    </div>
@endsection
