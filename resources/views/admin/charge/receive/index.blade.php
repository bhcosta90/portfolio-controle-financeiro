@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __('Relatório de Conta a Receber') }}</h1>
        <div class='float-right'>{!! register(route('admin.charge.receive.create'), __('Cadastrar Conta a Receber')) !!}</div>
    </div>
    @include('admin.charge.partial.charge-table', ['data' => $data, 'route' => 'admin.charge.receive'])
    @include('partials.paginate', ['data' => $data])
</div>
@endsection
