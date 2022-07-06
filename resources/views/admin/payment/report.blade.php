@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __('Relatório de Pagamento') }}</h1>
    </div>
    {!! $result !!}
</div>
@endsection
