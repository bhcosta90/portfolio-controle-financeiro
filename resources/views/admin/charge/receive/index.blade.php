@extends('adminlte::page')

@section('content')

@include('admin.charge.charge.filter', compact('total'))

<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __('Relatório de Recebimentos') }} <small class='text-muted'>R$ {{str()->numberEnToBr($total)}}</small></h1>
        <div class='float-right'>{!! register(route('charge.receive.create'), __('Cadastrar Recebimento')) !!}</div>
    </div>
    <table class='table table-hover mb-0 table-report'>
        <tr>
            <th>{{ __('Nome') }}</th>
            <th>{{ __('Descrição') }}</th>
            <th>{{ __('Frequência') }}</th>
            <th style='width:1px'>{{ __('Vencimento') }}</th>
            <th style='width:1px'>{{ __('Valor') }}</th>
            <th style='width:1px'>{{ __('Ações') }}</th>
        </tr>
        <tbody>
            @foreach($data as $rs)
            <tr>
                <td>{!! isExpired($rs->status, $rs->date_due, $rs->name) !!}</td>
                <td>{!! isExpired($rs->status, $rs->date_due, $rs->title) !!} @if($rs->parcel_total > 1)<small class='text-muted'>(Parcela {{$rs->parcel_actual}})</small>@endif</td>
                <td style='white-space: nowrap;'>{!! isExpired($rs->status, $rs->date_due, $rs->recurrence ?: '-') !!}</td>
                <td>{!! isExpired($rs->status, $rs->date_due, str()->date($rs->date_due)) !!}</td>
                <td style='white-space: nowrap;'>{!! isExpired($rs->status, $rs->date_due, str()->numberEnToBr($rs->value_charge - $rs->value_pay))!!} @if($rs->value_pay) <small class='text-warning'>({{ str()->numberEnToBr($rs->value_pay) }})</small> @endif</td>
                <td>
                    {!! links([
                        [
                            "link" => route('charge.receive.pay.show', $rs->uuid),
                            'btn' => 'btn-outline-success',
                            'icon' => 'far fa-money-bill-alt',
                        ],
                        "edit" => [
                            "link" => route('charge.receive.edit', $rs->uuid)
                        ],
                        "delete" => [
                            "link" => route('charge.receive.destroy', $rs->uuid)
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
