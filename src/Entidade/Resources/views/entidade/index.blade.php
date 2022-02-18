@extends('entidade::layouts.master')

@section('content')
    <div class='card'>
        <div class='card-header'>
            <div class='float-start'>{{ $title }}</div>
            <div class=float-end><a class='btn btn-outline-info btn-sm' href="{{ $add }}">Adicionar</a></div>
        </div>
        {!! $table->render() !!}
    </div>
@endsection
