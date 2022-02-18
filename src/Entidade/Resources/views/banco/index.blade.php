@extends('entidade::entidade.index', [
    'title' => 'Relatório de bancos',
    'add' => route('entidade.banco.create', ['tenant' => tenant()])
])
