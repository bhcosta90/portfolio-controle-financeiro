<table class='table table-report table-striped table-hover table-responsive-md mb-0'>

    <thead>
    <tr>
        <th>{{ __('Fornecedor') }}</th>
        <th>{{ __('Valor') }}</th>
        <th>{{ __('Vencimento') }}</th>
        <th>{{ __('Recorrência') }}</th>
        <th style='width:1px'>{{ __('Ações') }}</th>
    </tr>
    </thead>

    <tbody>
    @foreach($data as $rs)
        <tr class='{{ \Carbon\Carbon::now()->firstOfMonth()->format('Y-m-d') > $rs->date ? 'due-date' : '' }}'>
            <td>
                <div><small>{{ $rs->relationship_name }}</small></div>
                <div>{!! $rs->title !!}</div>
            </td>
            <td class='due-date'>
                R$&nbsp;{{ str()->numberBr($rs->value_charge) }}
                @if($rs->value_pay)
                    <small class='text-mutex'>(R$&nbsp;{{ str()->numberBr($rs->value_pay) }})</small>
                @endif
                @if($rs->parcel_total > 1)
                    <div><small class='text-muted'>Parcela: {{ $rs->parcel_actual }} / {{ $rs->parcel_total }}</small>
                    </div>
                @endif
            </td>
            <td class='due-date'>{{ str()->date($rs->date) }}</td>
            <td>{{ __($rs->recurrence_name ?: '-') }}</td>
            <td>
                {!! links([
                [
                    "link" => route($pay . '.show', $rs->id),
                    'btn' => 'btn-outline-success',
                    'icon' => 'far fa-money-bill-alt',
                ],
                    "edit" => [
                    "link" => route($route . '.edit', $rs->id)
                ],
                    "delete" => [
                    "link" => route($route . '.destroy', $rs->id)
                ]
                ]) !!}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
