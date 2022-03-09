@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12 mb-1">
            <h4 class="text-uppercase">Estatísticas do Sistema</h4>

        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 mb-4 col-sm-6 col-12 requisicao contapagarhojequantidade" data-route="{{route('resumo.index', ['tipo' => 'contapagarhojequantidade', 'tenant' => tenant()])}}">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="align-self-center col-3">
                                <i class="bi bi-calendar2-check-fill text-warning fs-1 float-start"></i>
                            </div>
                            <div class="col-9 text-end">
                                <h3> <span class="quantidade">...</span></h3>
                                <span>Contas à pagar (Hoje)</span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 mb-4 col-sm-6 col-12 requisicao contapagarvencidasquantidade" data-route="{{route('resumo.index', ['tipo' => 'contapagarvencidasquantidade', 'tenant' => tenant()])}}">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="align-self-center col-3">
                                <i class="bi bi-calendar-x-fill text-danger fs-1 float-start"></i>
                            </div>
                            <div class="col-9 text-end">
                                <h3> <span class="quantidade">...</span></h3>
                                <span>Contas à pagar vencidas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 mb-4 col-sm-6 col-12 requisicao contareceberhojequantidade" data-route="{{route('resumo.index', ['tipo' => 'contareceberhojequantidade', 'tenant' => tenant()])}}">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="align-self-center col-3">
                                <i class="bi bi-calendar2-check-fill text-warning fs-1 float-start"></i>
                            </div>
                            <div class="col-9 text-end">
                                <h3> <span class="quantidade">...</span></h3>
                                <span>Contas receber (Hoje)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-3 mb-4 col-sm-6 col-12 requisicao contarecebervencidasquantidade" data-route="{{route('resumo.index', ['tipo' => 'contarecebervencidasquantidade', 'tenant' => tenant()])}}">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="align-self-center col-3">
                                <i class="bi bi-calendar-x-fill text-danger fs-1 float-start"></i>
                            </div>
                            <div class="col-9 text-end">
                                <h3 class="quantidade">...</h3>
                                <span>Contas à receber vencidas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="stats-subtitle">
        <div class="row mb-2">
            <div class="col-12 mt-3 mb-1">
                <h4 class="text-uppercase">Estatísticas Mensais</h4>

            </div>
        </div>

        <div class="row">

            <div class="mb-4 col-xl-6 col-md-12 requisicao saldobancario" data-route="{{route('resumo.index', ['tipo' => 'saldobancario', 'tenant' => tenant()])}}">
                <div class="card overflow-hidden">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="row media align-items-stretch">
                                <div class="align-self-center col-1">
                                    <i class="text-calculado fa-solid fa-money-check-dollar fs-1 mr-2" data-class='valor_real'></i>
                                </div>
                                <div class="media-body col-6">
                                    <h4>Saldo Financeiro</h4>
                                    <span>Valor final do saldo financeiro após as contas a pagar</span>
                                </div>
                                <div class="text-end text-calculado col-5" data-class='valor_real'>
                                    <h2>R$ <span class="valor_formatado">...</span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 mb-4 col-md-12 requisicao lucro" data-route="{{route('resumo.index', ['tipo' => 'lucro', 'tenant' => tenant()])}}">
                <div class="card overflow-hidden">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="row media align-items-stretch">
                                <div class="align-self-center col-1">
                                    <i class="bi-calendar2-date text-primary fs-1 mr-2"></i>
                                </div>
                                <div class="media-body col-6">
                                    <h4>Lucro no mês</h4>
                                    <span>Total arrecado este mês</span>
                                </div>
                                <div class="text-end col-5">
                                    <h2><span class="text-calculado" data-class='valor_real'>R$ <span class="valor_formatado">...</span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 mb-4 col-md-12 requisicao contapagar" data-route="{{route('resumo.index', ['tipo' => 'contapagar', 'tenant' => tenant()])}}">
                <div class="card overflow-hidden">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="row media align-items-stretch">
                                <div class="align-self-center col-1">
                                    <i class="bi bi-calendar-week-fill text-danger fs-1 mr-2"></i>
                                </div>
                                <div class="media-body col-6">
                                    <h4>Contas à pagar</h4>
                                    <span>Total de <span class='quantidade'>...</span> contas no mês</span>
                                </div>
                                <div class="text-end col-5">
                                    <h2>R$ <span class="valor_formatado">...</span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4 col-xl-6 col-md-12 requisicao contareceber" data-route="{{route('resumo.index', ['tipo' => 'contareceber', 'tenant' => tenant()])}}">
                <div class="card overflow-hidden">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="row media align-items-stretch">
                                <div class="align-self-center col-1">
                                    <i class="bi bi-calendar-week-fill text-success fs-1 mr-2"></i>
                                </div>
                                <div class="media-body col-6">
                                    <h4>Contas à receber</h4>
                                    <span>Total de <span class='quantidade'></span> contas no mês</span>
                                </div>
                                <div class="text-end col-5">
                                    <h2>R$ <span class="valor_formatado">...</span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <!-- <div class="row">
            <div class="mb-4 col-xl-6 col-md-12">
                <div class="card overflow-hidden">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="row media align-items-stretch">
                                <div class="align-self-center col-1">
                                    <i class="bi bi-calendar-week-fill text-success fs-1 mr-2"></i>
                                </div>
                                <div class="media-body col-6">
                                    <h4>Contas à receber</h4>
                                    <span>Total de 3 Contas no Mês</span>
                                </div>
                                <div class="text-end col-5">
                                    <h2>R$ 657,33</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4 col-xl-6 col-md-12">
                <div class="card overflow-hidden">
                    <div class="card-content">
                        <div class="card-body cleartfix">
                            <div class="row media align-items-stretch">
                                <div class="align-self-center col-1">
                                    <i class="bi bi-calendar2-plus-fill text-success fs-1 mr-2"></i>
                                </div>
                                <div class="media-body col-6">
                                    <h4>Total de vendas</h4>
                                    <span>Vendas do Mês em R$</span>
                                </div>
                                <div class="text-end col-5">
                                    <h2>R$ 1.991,89</h2>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </div> -->
    </section>
</div>
@endsection

@section('js')
    <script type='text/javascript' src="{{ asset(mix('js/home.js')) }}"></script>
@endsection
