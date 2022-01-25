@extends('layouts.app')

@section('content')
    <div class='card'>
        <div class='card-header'>
            <h3 class='m-0'>{{ __('Cost register')}}</h3>
        </div>
        <div class='card-body'>
            <div class='form-group'>
                <label class='control-label'>{{ __('Customer name') }}</label>
                <h5>{{ $obj->customer_name }}</h5>
            </div>
            <div class='form-group'>
                <label class='control-label'>{{ __('Resume') }}</label>
                <h5>{{ $obj->resume }}</h5>
            </div>
            <div class='row'>
                <div class='form-group col-6'>
                    <label class='control-label'>{{ __('Value') }}</label>
                    <h5>{{ Str::numberEnToBr($obj->value) }}</h5>
                </div>
                <div class='form-group col-6'>
                    <label class='control-label'>{{ __('Due date') }}</label>
                    <h5>{{ (new \Carbon\Carbon($obj->due_date))->format('d/m/Y') }}</h5>
                </div>
            </div>
            {!! form($form) !!}
        </div>
    </div>
@endsection
