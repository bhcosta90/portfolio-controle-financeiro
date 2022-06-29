@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __('Relatório de Contas a Receber') }}</h1>
        <div class='float-right'>{!! register(route('admin.charge.receive.create'), __('Cadastrar Contas a Receber')) !!}</div>
    </div>
    <table class='table table-report table-striped table-hover table-responsive-md mb-0'>
        <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Valor') }}</th>
                <th>{{ __('Vencimento') }}</th>
                <th style='width:1px'>{{ __('Ações') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $rs)
            <tr>
                <td>{{ $rs->customer->name->value }}</td>
                <td>
                    R$&nbsp;{{ str()->numberBr($rs->value - $rs->pay) }}
                    @if($rs->pay)
                    <small class='text-mutex'>(R$&nbsp;{{ str()->numberBr($rs->pay) }})</small>
                    @endif
                </td>
                <td>{{ $rs->date->format('d/m/Y') }}</td>
                <td>
                    {!! links([
                        [
                            "link" => route('admin.charge.receive.pay.show', $rs->id),
                            'btn' => 'btn-outline-success',
                            'icon' => 'far fa-money-bill-alt',
                        ],
                        "edit" => [
                            "link" => route('admin.charge.receive.edit', $rs->id)
                        ],
                        "delete" => [
                            "link" => route('admin.charge.receive.destroy', $rs->id)
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
