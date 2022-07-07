@extends('layouts.app')

@section('content')
    <div class='card'>
        <div class='card-header'>
            <h1 class='float-left'>{{ __('Relatório de Conta a Pagar') }}</h1>
            <div
                class='float-right'>{!! register(route('admin.charge.payment.create'), __('Cadastrar Conta a Pagar')) !!}</div>
        </div>
        @include('admin.charge.partial.charge-table', ['data' => $data, 'route' => 'admin.charge.payment'])
        @include('partials.paginate', ['data' => $data])
    </div>
@endsection
