@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-left'><h3 class='m-0'>{{ __('Report of account')}}</h3></div>
        <div class='float-right'>
            <a href="{{ route('account.create') }}" class="btn btn-outline-secondary btn-sm">{{ __('New account') }}</a>
        </div>
    </div>
    {!! $data->render() !!}
</div>
@endsection
