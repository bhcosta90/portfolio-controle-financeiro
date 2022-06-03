@extends('adminlte::page')

@section('content')
<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __('Relatório de Fornecedor') }}</h1>
        <div class='float-right'>{!! register(route('admin.supplier.create'), __('Cadastrar Fornecedor')) !!}</div>
    </div>
    <table class='table table-hover mb-0 table-report'>
        <tr>
            <th>{{ __('Nome') }}</th>
            <th style='width:1px'>{{ __('Ações') }}</th>
        </tr>
        <tbody>
            @foreach($data as $rs)
            <tr>
                <td>{{ $rs->name }}</td>
                <td>
                    {!! links([
                        "edit" => [
                            "link" => route('admin.supplier.edit', $rs->id)
                        ],
                        "delete" => [
                            "link" => route('admin.supplier.destroy', $rs->id)
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
