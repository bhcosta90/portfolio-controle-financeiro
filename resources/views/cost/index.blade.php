@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-left'><h3 class='m-0'>{{ __('Report of cost')}}</h3></div>
        <div class='float-right'>
            <span class='text-muted'>{{__('New')}}</span>
            <a href="{{ route('cost.create.normal') }}" class='btn btn-outline-secondary btn-sm'>{{ __('Cost') }}</a>
            <a href="{{ route('cost.create.recursive') }}" class='btn btn-outline-info btn-sm'>{{ __('Recursive') }}</a>
            <a href="{{ route('cost.create.parcel') }}" class='btn btn-outline-warning btn-sm'>{{ __('Parcel') }}</a>
        </div>
    </div>
    @include('includes.charge.filter')
    {!! $data->render() !!}
</div>
@endsection
