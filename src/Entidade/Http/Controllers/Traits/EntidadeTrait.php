<?php

namespace Modules\Entidade\Http\Controllers\Traits;

use Costa\LaravelPackage\Traits\Support\TableTrait;
use Modules\Entidade\Models\Banco;

trait EntidadeTrait {

    use TableTrait;

    protected abstract function getTituloColunaNome(): string;

    protected function getTableColumns(): array
    {
        return [
            '' => [
                'action' => fn ($obj) => ativo($obj->entidade->ativo),
                'class' => 'min'
            ],
            $this->getTituloColunaNome() => fn ($obj) => $obj->entidade->nome,
            "Pessoa" => fn ($obj) => !is_array($obj->entidade->tipo) ? $obj->entidade->tipo : '-',
            "Documento" => fn ($obj) => $obj->entidade->documento ? $obj->entidade->documento : '-',
            "E-mail" => fn ($obj) => $obj->entidade->email ?: '-',
            "Telefone" => fn ($obj) => $obj->entidade->telefone ? $obj->entidade->telefone : '-',
            '_contatos' => [
                'action' => fn ($obj) => btnLinkIcon(route('entidade.contato.index', ['entidade' => $obj->entidade->uuid, 'tenant' => tenant()]), 'fa-solid fa-address-card', '', 'btn-outline-secondary btn-sm', '_blank'),
                'class' => 'min',
            ],
            '_edit' => [
                'action' => fn ($obj) => btnLinkEditIcon(route($obj instanceof Banco ? 'entidade.banco.edit' : 'entidade.entidade.edit', [$obj instanceof Banco ? 'banco' : 'entidade' => $obj->entidade->uuid, 'tenant' => tenant()])),
                'class' => 'min',
            ],
            '_delete' => [
                'action' => fn ($obj) => btnLinkDelIcon(route('entidade.entidade.destroy', ['entidade' => $obj->entidade->uuid, 'tenant' => tenant()])),
                'class' => 'min',
            ]
        ];
    }
}
