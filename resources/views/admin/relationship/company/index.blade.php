@extends(layout())

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='pull-left float-left float-start'>
            <h1>{{ __('Relatório de Empresas') }}</h1>
            @include('partials.filter', compact('filter'))
        </div>
        <div class='pull-right float-right float-end'>{!! register(route('admin.relationship.company.create'), __('Cadastrar Empresa')) !!}</div>
    </div>
    <div class="table-responsive-md">
        <table class='table table-report table-striped table-hover table-responsive-md mb-0'>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th style='width:1px'>{{ __('Valor') }}</th>
                    <th style='width:1px'>{{ __('Ações') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach($data as $rs)
                <tr>
                    <td>{{ $rs->name }}</td>
                    <td>R$&nbsp;{{ str()->numberBr($rs->value) }}</td>
                    <td>
                        {!! links([
                        "edit" => [
                        "link" => route('admin.relationship.company.edit', $rs->id)
                        ],
                        "delete" => [
                        "link" => route('admin.relationship.company.destroy', $rs->id)
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
