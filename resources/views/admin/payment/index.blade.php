@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __('Relatório de Pagamento') }}</h1>
        <div class='float-right'>{!! printOut(route('admin.payment.print', request()->all()), __('Imprimir')) !!}</div>
    </div>
    <table class='table table-report table-striped table-hover table-responsive-md mb-0'>
        <thead>
            <tr>
                <th>{{ __('Nome') }}</th>
                <th>{{ __('Banco') }}</th>
                <th>{{ __('Valor') }}</th>
                <th style='width:1px'>{{ __('Ações') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $rs)
            <tr>
                <td>
                     <div>
                       <div class='float-left'>
                            @switch($rs->status)
                                @case(1)
                                    <i class="far text-warning fa-clock"></i>
                                @break
                                @case(2)
                                    <i class="fas text-info fa-spinner"></i>
                                @break
                                @case(3)
                                    <i class="fas text-success fa-check"></i>
                                @break
                            @endswitch
                       </div>
                        <div class='float-left pl-2'>
                            @if($rs->relationship_name)
                                {{ $rs->relationship_name }}
                            @endif
                            <div>{{ $rs->title }}</div>
                        </div>
                     </div>
                </td>
                <td>
                    {{ $rs->bank_name ?: "-" }}
                    @if($rs->bank_name && $rs->value_bank)
                        <small class='text-muted'>(R$ {{ str()->numberBr($rs->value_bank) }})</small>
                    @endif
                </td>
                <td>{{ str()->numberBr($rs->value) }}</td>
                <td>
                    {!! links([
                        "delete" => [
                            "link" => route('admin.payment.destroy', $rs->id),
                            "hidden" => $rs->status > 1
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
