@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __('Relatório de Pagamento') }}</h1>
    </div>
    <table class='table table-hover mb-0 table-report'>
        <tr>
            <th style='width:1px'></th>
            <th>{{ __('Nome') }}</th>
            <th>{{ __('Descrição') }}</th>
            <th style='width:1px'>{{ __('Vencimento') }}</th>
            <th style='width:1px'>{{ __('Valor') }}</th>
            <th style='width:1px'>{{ __('Ações') }}</th>
        </tr>
        <tbody>
            @foreach($data as $rs)
            <tr>
                <td><i class="{{ $rs->completed ? "fas fa-check text-success" : "far fa-clock text-warning" }}"></i></td>
                <td>{{ $rs->name }}</td>
                <td>{{ $rs->title }}</td>
                <td>{{ str()->date($rs->date_schedule) }}</td>
                <td style='white-space: nowrap;'>{{ str()->numberEnToBr($rs->value_payment) }}</td>
                <td>
                    {!! links([
                        "delete" => [
                            "link" => route('admin.payment.destroy', $rs->id)
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
