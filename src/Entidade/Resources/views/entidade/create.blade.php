@extends('entidade::layouts.master')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'>{{ $title }}</div>
    </div>

    <div class='card-body'>
        @include('entidade::entidade.form', ['form' => $form])
    </div>
</div>
@endsection
