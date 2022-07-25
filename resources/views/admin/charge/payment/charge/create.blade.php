@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='pull-left float-left float-start'>
            <h1>{{ __('Cadastro de Pagamento') }}</h1>
        </div>
        <div class='pull-right float-right float-end'>{!! urlBank(route('admin.charge.payment.charge.index'), __('Todas os Pagamentos')) !!}</div>
    </div>
    <div class='card-body'>
        {!! form($form) !!}
    </div>
</div>
@endsection
