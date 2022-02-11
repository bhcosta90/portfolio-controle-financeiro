@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">{{ __('Dashboard') }}</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class='row'>
                <div class='col-3'>
                    <div class="card overdue">
                        <div class="card-body">
                            <h5 class="card-title">Cobranças vencidas</h5>
                            <h4>
                                <span class='valor_formatado mb-0 text_status_negativo'></span>
                                <span class='overdue_quantity text-muted'> |
                                    <small class='valor_atual' data-title='cobranças'>...</small>
                                </span>
                            </h4>
                        </div>
                    </div>
                </div>

                <div class='col-3'>
                    <div class="card income">
                        <div class="card-body">
                            <h5 class="card-title">Receitas do mês</h5>
                            <h4 class='valor_formatado mb-0 text_status_positivo'>...</h4>
                        </div>
                    </div>
                </div>

                <div class='col-3'>
                    <div class="card cost">
                        <div class="card-body">
                            <h5 class="card-title">Despesas do mês</h5>
                            <h4 class='valor_formatado mb-0 text_status_negativo'>...</h4>
                        </div>
                    </div>
                </div>

                <div class='col-3'>
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
