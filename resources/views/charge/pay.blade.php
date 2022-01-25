@extends('layouts.app')

@section('content')
    <div class='card'>
        <div class='card-header'>
            <h3 class='m-0'>{{ __('Payment charge')}}</h3>
        </div>
        <div class='card-body'>
            <div class='form-group'>
                <div>{{ __('Customer name') }}</div>
                <h4>{{ $obj->customer_name }}</h4>
            </div>
            <div class='form-group'>
                <div>{{ __('Resume') }}</div>
                <h4>{{ $obj->resume }}</h4>
            </div>
            <div class='row'>
                <div class='form-group col-6'>
                    <div>{{ __('Value') }}</div>
                    <h4>{{ Str::numberEnToBr($obj->value) }}</h4>
                </div>
                <div class='form-group col-6'>
                    <div>{{ __('Due date') }}</div>
                    <h4>{{ (new \Carbon\Carbon($obj->due_date))->format('d/m/Y') }}</h4>
                </div>
            </div>
            <hr />
            {!! form($form) !!}
        </div>
    </div>
@endsection
