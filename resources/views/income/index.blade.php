@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-left'><h3 class='m-0'>{{ __('Report of income')}}</h3></div>
        <div class='float-right'>
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-toggle="dropdown"
                    aria-expanded="false">
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
