@extends('cobranca::layouts.master')

@section('content')
    <nav>
        <ul class="nav nav-tabs">
            @foreach($bancos as $banco)
            <li class="nav-item">
                <a aria-label="{{$banco->nome_select}}" data-microtip-position="top" role="tooltip" title="{{$banco->nome_select}}" class="nav-link {{$banco->uuid == $ativo ?? null ? 'active' : ''}}" href="{{ route('cobranca.contabancaria.movimentacao.show', ['movimentacao' => $banco->uuid, 'tenant' => tenant(), 'data_inicio' => (new \Carbon\Carbon)->now()->format('Y-m-d'), 'data_final' => (new \Carbon\Carbon)->now()->format('Y-m-d')]) }}">
                    {{$banco->banco->entidade->nome}}
                </a>
            </li>
            @endforeach
        </ul>
    </nav>
    <div class="tab-content">
        {!! $table->render() !!}
    </div>
@endsection
