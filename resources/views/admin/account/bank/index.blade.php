@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='pull-left float-left float-start'>
            <h1>{{ __('Relatório de Contas Bancária') }}</h1>
            @include('partials.filter', compact('filter'))
        </div>
        <div class='pull-right float-right float-end'>{!! register(route('admin.account.bank.create'), __('Cadastrar Conta Bancária')) !!}</div>
    </div>
    <div class="table-responsive-md">
        <table class='table table-report table-striped table-hover table-responsive-md mb-0'>
            <thead>
                <tr>
                    <th style='width:1px'></th>
                    <th>{{ __('Name') }}</th>
                    <th style='width:1px'>{{ __('Valor') }}</th>
                    <th style='width:1px'>{{ __('Ações') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach($data as $rs)
                <tr>
                    <td><div style='height:10px;width:10px;margin-top: 5px' class='bg-{{ $rs->active ? 'success': 'danger' }}'></div></td>
                    <td>{{ $rs->name }}</td>
                    <td>R$&nbsp;{{ str()->numberBr($rs->account_value) }}</td>
                    <td>
                        {!! links([
                        "edit" => [
                        "link" => route('admin.account.bank.edit', $rs->id)
                        ],
                        "delete" => [
                        "link" => route('admin.account.bank.destroy', $rs->id)
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
