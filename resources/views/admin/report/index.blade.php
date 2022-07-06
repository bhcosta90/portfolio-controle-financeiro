@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <h1 class='float-left'>{{ __($title) }}</h1>
        {{-- <div class='float-right'>{!! printOut(route('admin.payment.print', request()->all()), __('Imprimir')) !!}</div> --}}
    </div>
    {!! $render !!}
</div>
@endsection
