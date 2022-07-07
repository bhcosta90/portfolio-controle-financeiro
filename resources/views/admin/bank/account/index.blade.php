@extends('layouts.app')

@section('content')
    <div class='card'>
        <div class='card-header'>
            <h1 class='float-left'>{{ __('Relatório de Conta Bancária') }}</h1>
            <div
                class='float-right'>{!! register(route('admin.bank.account.create'), __('Cadastrar Conta Bancária')) !!}</div>
        </div>
        <table class='table table-report table-striped table-hover table-responsive-md mb-0'>
            <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Valor') }}</th>
                <th style='width:1px'>{{ __('Ações') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach($data as $rs)
                <tr>
                    <td>{{ $rs->name }}</td>
                    <td>R$ {{ str()->numberBr($rs->value) }}</td>
                    <td>
                        {!! links([
                            "edit" => [
                                "link" => route('admin.bank.account.edit', $rs->id)
                            ],
                            "delete" => [
                                "link" => route('admin.bank.account.destroy', $rs->id)
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
