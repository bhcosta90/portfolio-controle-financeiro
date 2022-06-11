@extends('layouts.app')

@section('content')
    <div class='card'>
        <div class='card-header'>{{ __('Cadastrar Conta Bancária') }}</div>
        <div class='card-body'>{!! form($form) !!}</div>
    </div>
@endsection