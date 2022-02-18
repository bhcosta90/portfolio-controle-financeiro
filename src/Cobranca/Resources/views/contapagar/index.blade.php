@extends('cobranca::layouts.master')

@section('content')
    @include('cobranca::cobranca.filter', [
        'nome' => 'Fornecedor',
        'route' => route('api.entidade.fornecedor.search', ['tenant' => tenant()]),
        'ref' => (string) str()->uuid(),
        'routeResumo' => route('cobranca.conta.pagar.total', ['tenant' => tenant()])
    ])
    <div class='card'>
        <div class='card-header'>
            <div class='float-start'>Relatório de contas a pagar</div>
            <div class=float-end><a class='btn btn-outline-info btn-sm' href="{{ route('cobranca.conta.pagar.create', ['tenant' => tenant()]) }}">Adicionar</a></div>
        </div>
        {!! $table->render() !!}
    </div>
@endsection
