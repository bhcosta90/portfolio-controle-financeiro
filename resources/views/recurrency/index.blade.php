@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'><h4>Relatório de frequências</h4></div>
        <div class='float-end'>{!! btnLinkAddIcon(route('recurrency.create'), 'Adicionar frequência') !!}</div>
    </div>
    {!! $table->render() !!}
</div>
@endsection
