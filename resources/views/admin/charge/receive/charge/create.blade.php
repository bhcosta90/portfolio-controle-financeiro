@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='pull-left float-left float-start'>
            <h1>{{ __('Cadastro de Recebimento') }}</h1>
        </div>
        <div class='pull-right float-right float-end'>{!! urlBank(route('admin.charge.receive.charge.index'), __('Todos os Recebimentos')) !!}</div>
    </div>
    <div class='card-body'>
        {!! form($form) !!}
    </div>
</div>
@endsection
