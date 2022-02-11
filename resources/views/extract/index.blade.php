@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>
        <div class='float-start'><h4>Movimentação financeira</h4></div>
    </div>
    {!! $table->render() !!}
</div>
@endsection
