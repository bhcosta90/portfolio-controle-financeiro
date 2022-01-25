@extends('layouts.app')

@section('content')
    <div class='card'>
        <div class='card-header'>
            <h3 class='m-0'>{{ __('Cost register')}}</h3>
        </div>
        <div class='card-body'>{!! form($form) !!}</div>
    </div>
@endsection
