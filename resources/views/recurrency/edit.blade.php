@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'><h4>Editar frequências</h4></div>
    </div>
    <div class='card-body'>{!! form($form) !!}</div>
</div>
@endsection
