@extends('adminlte::page')

@section('content')
    <div class='card'>
        <div class='card-header'>{{ __('Editar Fornecedor') }}</div>
        <div class='card-body'>{!! form($form) !!}</div>
    </div>
@endsection