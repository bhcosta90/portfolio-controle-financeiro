@extends('layouts.app')

@section('content')
    @include('charge.index', [
        'data' => $data,
        'create' => route('income.create'),
        'empty' => __('Não há nenhuma receita registrada neste período'),
        'title' => __('receita'),
        'customer' => __("Cliente"),
    ])
@endsection
