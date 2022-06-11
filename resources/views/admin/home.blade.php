@extends('layouts.app')

@section('content')
    <div id='home'>
    <div class="container-fluid">
        <div class="row mb-2">
            <h3 class="m-0">{{__('Estatísticas do Sistema')}}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-6 request" data-route="{{ route('api.charge.payment.resume', ['type' => 'today']) }}">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 class='quantity'>...</h3>
                    <p>{{ __('Contas à pagar (Hoje)') }}</p>
                </div>
                <div class="icon">
                    <i class="bi bi-calendar2-check-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6 request" data-route="{{ route('api.charge.payment.resume', ['type' => 'due-date']) }}">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 class='quantity'>...</h3>
                    <p>{{ __('Contas à pagar vencidas') }}</p>
                </div>
                <div class="icon">
                    <i class="bi bi-calendar-x-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6 request" data-route="{{ route('api.charge.receive.resume', ['type' => 'today']) }}">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 class='quantity'>...</h3>
                    <p>{{ __('Contas receber (Hoje)') }}</p>
                </div>
                <div class="icon">
                    <i class="bi bi-calendar2-check-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6 request" data-route="{{ route('api.charge.receive.resume', ['type' => 'due-date']) }}">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3 class='quantity'>...</h3>
                    <p>{{ __('Contas à receber vencidas') }}</p>
                </div>
                <div class="icon">
                    <i class="bi bi-calendar-x-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <hr />
    <div class="container-fluid">
        <div class="row mb-2">
            <h3 class="m-0">{{ __('Estatísticas Mensais') }}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-6 request" data-route="{{ route('api.payment.financialbalance', ['month' => request('month')]) }}">
            <div class="small-box box bg-secondary" data-success="bg-success" data-danger="bg-danger">
                <div class="inner">
                    <h3 class='total_real' data-prefix='R$'>...</h3>
                    <p>{{ __('Saldo Bancário') }}</p>
                </div>
                <div class="icon">
                    <i class="far fa-money-bill-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-6 request" data-route="{{ route('api.payment.profitmonth', ['month' => request('month')]) }}">
            <div class="small-box box bg-secondary" data-success="bg-success" data-danger="bg-danger">
                <div class="inner">
                    <h3 class='total_real' data-prefix='R$'>...</h3>
                    <p>{{ __('Lucro no mês') }}</p>
                </div>
                <div class="icon">
                    <i class="far fa-calendar-alt"></i>
                </div>
            </div>
        </div>

    </div>
    <hr />
    <div class='row'>
        <div class="col-4 request" data-route="{{ route('api.payment.calcule', ['month' => request('month')]) }}">
            <div class="small-box box bg-secondary" data-success="bg-success" data-danger="bg-danger">
                <div class="inner">
                    <h3 class='total_real' data-prefix='R$'>...</h3>
                    <p>{{ __('Saldo mensal') }}</p>
                </div>
                <div class="icon">
                    <i class="bi bi-calendar2-check-fill"></i>
                </div>
            </div>
        </div>
        
        <div class="col-4 request" data-route="{{ route('api.charge.payment.resume', ['type' => 'value', 'month' => request('month')]) }}">
            <div class="small-box box bg-secondary" data-success="bg-danger" data-danger="bg-success">
                <div class="inner">
                    <h3 class='total_real' data-prefix='R$'>...</h3>
                    <p>{{ __('Contas à pagar') }}</p>
                </div>
                <div class="icon">
                    <i class="bi bi-calendar2-check-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-4 request" data-route="{{ route('api.charge.receive.resume', ['type' => 'value', 'month' => request('month')]) }}">
            <div class="small-box box bg-secondary" data-success="bg-success" data-danger="bg-danger">
                <div class="inner">
                    <h3 class='total_real' data-prefix='R$'>...</h3>
                    <p>{{ __('Contas à receber') }}</p>
                </div>
                <div class="icon">
                    <i class="bi bi-calendar-x-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    <script type='text/javascript' src="{{ asset(mix('js/home.js')) }}"></script>
@endsection
