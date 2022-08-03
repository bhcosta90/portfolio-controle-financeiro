@extends(layout())

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='pull-left float-left float-start'>
            <h1>{{ __('Relatório de Contas Bancária') }}</h1>
            @include('partials.filter', compact('filter'))
        </div>
        <div class='pull-right float-right float-end'>{!! register(route('admin.charge.recurrence.create'), __('Cadastrar Recorrência')) !!}</div>
    </div>
    <div class="table-responsive-md">
        <table class='table table-report table-striped table-hover table-responsive-md mb-0'>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th style='width:1px'>{!! __('Quantidade&nbsp;de&nbsp;Dias') !!}</th>
                    <th style='width:1px'>{{ __('Ações') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach($data as $rs)
                <tr>
                    <td>{{ $rs->name }}</td>
                    <td>{{ $rs->days }}</td>
                    <td>
                        {!! links([
                        "edit" => [
                        "link" => route('admin.charge.recurrence.edit', $rs->id)
                        ],
                        "delete" => [
                        "link" => route('admin.charge.recurrence.destroy', $rs->id)
                        ]
                        ]) !!}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('partials.paginate', ['data' => $data])
</div>
@endsection
