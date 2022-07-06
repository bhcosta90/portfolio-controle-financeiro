@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __('Relatório de Recorrência') }}</h1>
        <div class='float-right'>{!! register(route('admin.charge.recurrence.create'), __('Cadastrar Recorrência')) !!}</div>
    </div>
    <table class='table table-report table-striped table-hover table-responsive-md mb-0'>
        <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Quantidade de Dias') }}</th>
                <th style='width:1px'>{{ __('Ações') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $rs)
            <tr>
                <td>{{ $rs->name }}</td>
                <td>{{ $rs->days }}</td>
                <td>
                    {!! links([
                        "edit" => [
                            "link" => route('admin.charge.recurrence.edit', $rs->id)
                        ],
                        "delete" => [
                            "link" => route('admin.charge.recurrence.destroy', $rs->id)
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
