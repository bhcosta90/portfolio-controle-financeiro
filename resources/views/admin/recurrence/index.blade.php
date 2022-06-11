@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __('Relatório de Recorrências') }}</h1>
        <div class='float-right'>{!! register(route('admin.recurrence.create'), __('Cadastrar Recorrência')) !!}</div>
    </div>
    <table class='table table-hover mb-0 table-report'>
        <tr>
            <th>{{ __('Nome') }}</th>
            <th style='width:1px'>{{ __('Dias') }}</th>
            <th style='width:1px'>{{ __('Ações') }}</th>
        </tr>
        <tbody>
            @foreach($data as $rs)
            <tr>
                <td>{{ $rs->name }}</td>
                <td>{{ $rs->days }}</td>
                <td>
                    {!! links([
                        "edit" => [
                            "link" => route('admin.recurrence.edit', $rs->id)
                        ],
                        "delete" => [
                            "link" => route('admin.recurrence.destroy', $rs->id)
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
