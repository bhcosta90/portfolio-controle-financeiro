@extends('layouts.app')

@section('content')
    <div class='card'>
        <div class='card-header'>
            <h1 class='float-left'>{{ __('Editar Empresa') }}</h1>
        </div>
        <div class='card-body'>
            {!! form($form) !!}
        </div>
    </div>
@endsection
