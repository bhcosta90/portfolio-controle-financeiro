@extends('layouts.app')

@section('content')
    @include('charge.index', [
        'data' => $data,
        'create' => route('cost.create'),
        'empty' => __('Não há nenhuma despesa registrada neste período'),
        'title' => __('despesa'),
        'customer' => "Fornecedor",
    ])
@endsection
