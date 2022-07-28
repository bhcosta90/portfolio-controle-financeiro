@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>{{ __('Pagar - Conta a Receber') }}</div>
    <div class='card-body'>
        <div class="row">
            <div class="col-md-6">
                <label class="control-label">Fornecedor</label>
                <h4>{{$model->customerName}}</h4>
            </div>
            <div class="col-md-6">
                <label class="control-label">Descrição</label>
                <h4>{{$model->title}}</h4>
            </div>
            <div class="col-md-6">
                <label class="control-label">Valor</label>
                <h4><small>R$ </small>{{str()->numberBr($model->value - $model->pay)}}</h4>
            </div>
            <div class="col-md-6">
                <label class="control-label">Vencimento</label>
                <h4>{{ str()->date($model->date) }}</h4>
            </div>
        </div>
        <hr />
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-item nav-link {{ ($route = session('route')) != 'admin.charge.receive.pay.partial.store' ? "active" : ""}}" id="home" data-bs-toggle="tab" data-bs-target="#home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                    {!! __('Pagamento total') !!}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-item nav-link {{ $route == 'admin.charge.receive.pay.partial.store' ? "active" : ""}}" id="profile" data-bs-toggle="tab" data-bs-target="#profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">
                    {{ __('Pagamento Parcial') }}
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade {{ $route != 'admin.charge.receive.pay.partial.store' ? "show active" : ""}}" id="home-tab" role="tabpanel" aria-labelledby="home-tab">
                <div class="card">
                    <div class="card-body">
                        <h4><small>Você vai pagar o valor total de</small> R$ {{ str()->numberBr($model->value - $model->pay) }}</h4>
                        <div class="mt-3">
                            {!! form_start($formTotal) !!}
                            <div class="row">
                                <div class='col-md-6'>{!! form_row($formTotal->bank_id) !!}</div>
                                <div class='col-md-6'>{!! form_row($formTotal->date_scheduled) !!}</div>
                            </div>
                            {!! form_end($formTotal) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade {{ $route == 'admin.charge.receive.pay.partial.store' ? "show active" : ""}}" id="profile-tab" role="tabpanel" aria-labelledby="profile-tab">
                <div class="card">
                    <div class="card-body">
                        {!! form_start($formPartial) !!}
                        <div class='row'>
                            <div class='col-md-6'>{!! form_row($formPartial->value_pay) !!}</div>
                            <div class='col-md-6'>{!! form_row($formPartial->bank_id) !!}</div>
                            <div class="col-md-6">
                                <label>Haverá novo pagamento?</label>
                                <div>
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-outline-secondary active">
                                            <input type="radio" value="1" name="new_payment" id="option1" autocomplete="off" checked> Sim
                                        </label>
                                        <label class="btn btn-outline-secondary">
                                            <input type="radio" value="0" name="new_payment" id="option2" autocomplete="off"> Não
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class='col-md-6'>{!! form_row($formPartial->date_next) !!}</div>
                        </div>
                        <hr />
                        {!! form_end($formPartial) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
