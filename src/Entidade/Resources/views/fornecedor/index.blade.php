@extends('entidade::entidade.index', [
    'title' => 'Relatório de fornecedor',
    'add' => route('entidade.fornecedor.create', ['tenant' => tenant()])
])
