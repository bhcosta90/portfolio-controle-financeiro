@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='pull-left float-left float-start'>
            <h1>{{ __('Editar Cliente') }}</h1>
        </div>
        <div class='pull-right float-right float-end'>{!! urlBank(route('admin.relationship.customer.index'), __('Todos os clientes')) !!}</div>
    </div>
    <div class='card-body'>
        {!! form($form) !!}
    </div>
</div>
@endsection
