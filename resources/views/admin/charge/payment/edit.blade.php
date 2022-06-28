@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>{{ __('Editar Conta a Receber') }}</div>
    <div class='card-body'>
        {!! form($form) !!}
    </div>
</div>
@endsection