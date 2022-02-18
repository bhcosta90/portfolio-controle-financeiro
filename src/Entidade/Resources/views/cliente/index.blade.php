@extends('entidade::entidade.index', [
    'title' => 'Relatório de cliente',
    'add' => route('entidade.cliente.create', ['tenant' => tenant()])
])
