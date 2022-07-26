@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='pull-left float-left float-start'>
            <h1>{{ __('Contas a Receber') }}</h1>
            @include('partials.filter', compact('filter'))
        </div>
        <div class='pull-right float-right float-end'>
            {!! register(route('admin.charge.receive.charge.create'), __('Cadastrar Conta a Receber')) !!}
            <div><small class='text-muted'>R$ {{str()->numberBr($total)}}</small></div>
        </div>
    </div>
    @include('admin.charge.partial.charge-table', ['data' => $data, 'route' => 'admin.charge.receive.charge', 'pay' => 'admin.charge.receive.pay'])
    @include('partials.paginate', ['data' => $data])
</div>
@endsection
