@extends('layouts.app')

@section('content')

<div class='card form-group' id='account_resume'>
    <div class='card-body'>
        <div class='row'>
            <div class='col-6'>
                <h5>Minha conta</h5>
                <h3>
                    <span><small>R$ </small> <span id='my_account_total'>...</span></span>
                </h3>
            </div>

            <div class='col-6'>
                <h5>Pagamento</h5>
                <h3>
                    <span><small>R$ </small> <span id='my_account_pay'>...</span></span>
                </h3>
            </div>
        </div>

        <hr />

        <div class='row'>
            <div class='col-6 form-group'>
                <h5>Receitas</h5>
                <h3>
                    <span class='text-success'><small>R$ </small> <span id='incoming_total'>...</span></span>
                </h3>
            </div>
            <div class='col-6 form-group'>
                <h5>Receitas Vencidas</h5>
                <h3>
                    <span class='text-danger'><small>R$ </small> <span id='incoming_payable'>...</span></span>
                </h3>
            </div>
        </div>
    </div>
</div>
<div class='card'>
    <div class='card-header'>
        <div class='float-left'>
            <h3 class='m-0'>{{ __('Report of income')}}</h3>
        </div>
        <div class='float-right'>
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle"
                    data-toggle="dropdown" aria-expanded="false">
                    {{ __('New income') }}
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btnGroupDrop1">
                    <a href="{{ route('income.create.normal') }}" class="dropdown-item">{{ __('Income') }}</a>
                    <a href="{{ route('income.create.recursive') }}" class="dropdown-item">{{ __('Recursive') }}</a>
                    <a href="{{ route('income.create.parcel') }}" class="dropdown-item">{{ __('Parcel') }}</a>
                </div>
            </div>
        </div>
    </div>
    @include('includes.charge.filter')
    {!! $data->render() !!}
</div>
@endsection
