@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-left'><h3 class='m-0'>{{ __('Report of income')}}</h3></div>
        <div class='float-right'>
            <span class='text-muted'>{{__('New')}}</span>
            <a href="{{ route('income.create.normal') }}" class='btn btn-outline-secondary btn-sm'>{{ __('Income') }}</a>
            <a href="{{ route('income.create.recursive') }}" class='btn btn-outline-info btn-sm'>{{ __('Recursive') }}</a>
            <a href="{{ route('income.create.parcel') }}" class='btn btn-outline-warning btn-sm'>{{ __('Parcel') }}</a>
        </div>
    </div>
    @include('includes.charge.filter')
    {!! $data->render() !!}
</div>
@endsection
