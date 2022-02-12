@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">{{ __('Resumo financeiro') }}</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class='row'>
                <div class='col-md-3 mb-1'>
                    <div class="card overdue">
                        <div class="card-body">
                            <h5 class="card-title">Cobranças vencidas</h5>
                            <h4 class='mb-0'>
                                <span class='valor_formatado text_status_negativo'></span>
                                <span class='overdue_quantity text-muted'> |
                                    <small class='valor_atual' data-title='cobranças'>...</small>
                                </span>
                            </h4>
                        </div>
                    </div>
                </div>

                <div class='col-md-3 mb-1'>
                    <div class="card income">
                        <div class="card-body">
                            <h5 class="card-title">Receitas do mês</h5>
                            <h4 class='valor_formatado mb-0 text_status_positivo'>...</h4>
                        </div>
                    </div>
                </div>

                <div class='col-md-3 mb-1'>
                    <div class="card cost">
                        <div class="card-body">
                            <h5 class="card-title">Despesas do mês</h5>
                            <h4 class='valor_formatado mb-0 text_status_negativo'>...</h4>
                        </div>
                    </div>
                </div>

                <div class='col-md-3 mb-1'>
                    <div class="card resume">
                        <div class="card-body">
                            <h5 class="card-title">Conta bancária</h5>
                            <h4 class='valor_formatado mb-0 text_status_positivo'>...</h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @if(count($shareds))
    <div class="card mt-3">
        <div class="card-header">{{ __('Solicitação pendente de compartilhamento') }}</div>

        <div class="card-body">
            <div class='alert alert-info'>{{ __('Você aceitando esses compartilhamentos, voce vai conseguir visualizar todas as cobranças do usuário e também as movimentações, no entando, o usuário também irá poder visualizar, caso você não deseja que ele visualize os seus dados, após aprovação, vai em ser perfil e deleta o compartilhamento com ele') }}</div>
        </div>

        <table class='table table-striped table-hover m-0'>
            <tr>
                <th>Nome</th>
                <th>E-mail</th>
                <td class='min'></td>
            </tr>
            @foreach($shareds as $shared)
            <tr>
                <td style='width:50%'>{{ $shared->userOrigin->name }}</td>
                <td style='width:50%'>{{ $shared->userOrigin->email }}</td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <div>{!!btnLinkDelIcon('', 'fa-solid fa-check', 'btn btn-sm btn-outline-success btn-link-delete', null, 'Tem certeza que deseja aprovar esse compartilhamento?')!!}</div>
                        <div>{!!btnLinkDelIcon('', 'fa-solid fa-xmark', 'btn btn-sm btn-outline-danger btn-link-delete')!!}</div>
                        {{-- <button type="button" class="btn btn-sm btn-outline-success">{{ __('Aceitar') }}</button>
                        <button type="button" class="btn btn-sm btn-outline-danger">{{ __('Recusar') }}</button> --}}
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <input type='hidden' id='date_start' value="{{ $dateStart }}">
    <input type='hidden' id='date_end' value="{{ $dateEnd }}">
@endsection

@section('js')
<script>
$(function(){
    const dates = [
        {
            'action': 'overdue',
        },
        {
            'action': 'income'
        },
        {
            'action': 'cost'
        },
        {
            'action': 'resume'
        },
        {
            'action': 'overdue_quantity'
        }
    ];

    var promiseArray = [];

    $.each(dates, function(index, item){
        item.date_start = $('#date_start').val();
        item.date_end = $('#date_end').val();

        const promisse = new Promise(function(resolve, reject){
            return $.get('/api/charge', item).then((json) => resolve(json)).catch((json) => reject(json));
        })
        promiseArray.push(promisse)
    });

    Promise.all(promiseArray).then(function(values){
        $.each(values, function(_, item){
            const div = $(`.${item.data.action}`);
            div.find('.valor_formatado').html(item.data.format);
            div.find('.valor_atual').html(`${item.data.value}`);
            if(div.find('.valor_atual').data('title') !== undefined) {
                div.find('.valor_atual').append(' ' + div.find('.valor_atual').data('title'));
            }

            if(item.data.value > 0) {
                div.find('.text_status_negativo').addClass('text-danger');
                div.find('.text_status_positivo').addClass('text-success');
            } else {
                div.find('.text_status_negativo').addClass('text-success');
                div.find('.text_status_positivo').addClass('text-danger');
            }
        })
    }).catch(function(reason) {
        console.log(reason)
    })
})
</script>
@endsection
