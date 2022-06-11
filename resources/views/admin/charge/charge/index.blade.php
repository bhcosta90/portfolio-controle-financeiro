@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __('Relatório de Cobranças') }}</h1>
        <div class='float-right'>{!! register(route('admin.charge.charge.create'), __('Cadastrar Cobrança')) !!}</div>
    </div>
    <table class='table table-hover mb-0 table-report'>
        <tr>
            <th>{{ __('Nome') }}</th>
            <th>{{ __('Descrição') }}</th>
            <th>{{ __('Frequência') }}</th>
            <th style='width:1px'>{{ __('Tipo') }}</th>
            <th style='width:1px'>{{ __('Vencimento') }}</th>
            <th style='width:1px'>{{ __('Valor') }}</th>
            <th style='width:1px'>{{ __('Ações') }}</th>
        </tr>
        <tbody>
            @foreach($data as $rs)
            <tr>
                <td>{{ $rs->name }}</td>
                <td>{{ $rs->title }} @if($rs->parcel_actual)<small class='text-muted'>(Parcela {{$rs->parcel_actual}})</small>@endif</td>
                <td style='white-space: nowrap;'>{{ $rs->recurrence ?: 'Uma vez' }}</td>
                <td>{{ $rs->type }}</td>
                <td>{{ str()->date($rs->date_due) }}</td>
                <td style='white-space: nowrap;'>{{ str()->numberEnToBr($rs->value_charge - $rs->value_pay) }} @if($rs->value_pay) <small class='text-warning'>({{ str()->numberEnToBr($rs->value_pay) }})</small> @endif</td>
                <td>
                    {!! links([
                        [
                            "link" => route('admin.charge.pay.show', $rs->id),
                            'btn' => 'btn-outline-success',
                            'icon' => 'far fa-money-bill-alt',
                        ],
                        "edit" => [
                            "link" => route('admin.charge.charge.edit', $rs->id)
                        ],
                        "delete" => [
                            "link" => route('admin.charge.charge.destroy', $rs->id)
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
