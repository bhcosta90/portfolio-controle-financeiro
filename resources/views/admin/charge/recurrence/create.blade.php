@extends(layout())

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='pull-left float-left float-start'>
            <h1>{{ __('Cadastro de Recorrência') }}</h1>
        </div>
        <div class='pull-right float-right float-end'>{!! urlBack(route('admin.charge.recurrence.index'), __('Todos as contas bancária')) !!}</div>
    </div>
    <div class='card-body'>
        {!! form($form) !!}
    </div>
</div>
@endsection
