@extends(layout())

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='pull-left float-left float-start'>
            <h1>{{ __('Editar Empresa') }}</h1>
        </div>
        <div class='pull-right float-right float-end'>{!! urlBack(route('admin.relationship.company.index'), __('Todas as Empresas')) !!}</div>
    </div>
    <div class='card-body'>
        {!! form($form) !!}
    </div>
</div>
@endsection
