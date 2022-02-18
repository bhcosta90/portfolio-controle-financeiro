@extends('cobranca::layouts.master')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'>Cadastro contas a pagar</div>
    </div>

    <div class='card-body'>
        @include('cobranca::cobranca.create', [
            'form' => $form,
        ])
    </div>
</div>
@endsection
