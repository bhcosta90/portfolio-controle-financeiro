@extends('adminlte::page')

@section('content')
    This is your multi-tenant application. The id of the current tenant is {{tenant('id')}}
@endsection