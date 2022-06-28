@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __('Relatório de Cliente') }}</h1>
        <div class='float-right'>{!! register(route('admin.relationship.customer.create'), __('Cadastrar Cliente')) !!}</div>
    </div>
    <table class='table table-report table-striped table-hover table-responsive-md mb-0'>
        <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Documento') }}</th>
                <th style='width:1px'>{{ __('Ações') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $rs)
            <tr>
                <td>{{ $rs->name->value }}</td>
                <td>{{ $rs->document?->document ?: '-' }}</td>
                <td>
                    {!! links([
                        "edit" => [
                            "link" => route('admin.relationship.customer.edit', $rs->id)
                        ],
                        "delete" => [
                            "link" => route('admin.relationship.customer.destroy', $rs->id)
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
