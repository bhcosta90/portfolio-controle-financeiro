@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='pull-left float-left float-start'>
            <h1>{{ __('Relatório de Pagamento') }}</h1>
            @include('partials.filter', compact('filter'))
        </div>
        <div class='pull-right float-right float-end'>{!! printOut(route('admin.report.index', [
            'report' => '00001A',
            'render' => 'html'
        ] + request()->except('token')), __('Imprimir')) !!}</div>
    </div>
    <div class="table-responsive-md">
        <table class='table table-report table-striped table-hover table-responsive-md mb-0'>
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Banco') }}</th>
                    <th style='width:1px'>{{ __('Valor') }}</th>
                    <th style='width:1px'>{{ __('Ações') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach($data as $rs)
                <tr>
                    <td>
                        <div>
                            <div class='float-left float-start'>
                                @switch($rs->status)
                                    @case(1)
                                        <i class="far text-warning fa-clock"></i>
                                        @break
                                    {{-- @case(2)
                                        <i class="fas text-info fa-spinner"></i>
                                        @break --}}
                                    @case(2 )
                                        <i class="fas text-success fa-check"></i>
                                        @break
                                @endswitch
                            </div>
                            <div class='float-left float-start p-2' style='padding-top:0!important;padding-right:0!important;padding-bottom:0!important;'>
                                @if($rs->relationship_name)
                                    {{ $rs->relationship_name }}
                                @endif
                                <div>{{ __($rs->title) }}</div>
                            </div>
                        </div>    
                    </td>
                    <td>{{ $rs->bank_name ?: '-' }}</td>
                    <td>R$&nbsp;{{ $rs->type == 2 ? '-' : '' }}{{ str()->numberBr($rs->value) }}</td>
                    <td>
                        {!! links([
                        "delete" => [
                            "link" => route('admin.transaction.destroy', $rs->id),
                            "hidden" => $rs->status > 1
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
