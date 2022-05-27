@extends('adminlte::page')

@section('content')
<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __('Relatório de Conta Bancária') }}</h1>
        <div class='float-right'>{!! register(route('bank.create'), __('Cadastrar Conta Bancária')) !!}</div>
    </div>
    <table class='table table-hover mb-0 table-report'>
        <tr>
            <th>{{ __('Nome') }}</th>
            <th style='width:1px'>{{ __('Valor') }}</th>
            <th style='width:1px'>{{ __('Ações') }}</th>
        </tr>
        <tbody>
            @foreach($data as $rs)
            <tr>
                <td>{{ $rs->name }}</td>
                <td>{{ str()->numberEnToBr($rs->value) }}</td>
                <td>
                    {!! links([
                        "edit" => [
                            "link" => route('bank.edit', $rs->uuid)
                        ],
                        "delete" => [
                            "link" => route('bank.destroy', $rs->uuid)
                        ]
                    ]) !!}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @include('partials.paginate', ['data' => $data])
</div>
@endsection
