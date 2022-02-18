@extends('cobranca::layouts.master')

@section('content')
    @include('cobranca::cobranca.filter', [
        'nome' => 'Cliente',
        'route' => route('api.entidade.cliente.search', ['tenant' => tenant()]),
        'ref' => (string) str()->uuid(),
        'routeResumo' => route('cobranca.conta.receber.total', ['tenant' => tenant()])
    ])

    <div class='card'>
        <div class='card-header'>
            <div class='float-start'>Relatório de contas a receber</div>
            <div class=float-end><a class='btn btn-outline-info btn-sm' href="{{ route('cobranca.conta.receber.create', ['tenant' => tenant()]) }}">Adicionar</a></div>
        </div>
        {!! $table->render() !!}

    </div>
@endsection
