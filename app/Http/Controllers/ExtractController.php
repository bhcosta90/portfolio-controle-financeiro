<?php

namespace App\Http\Controllers;

use App\Services\ExtractService;
use Costa\LaravelPackage\Traits\Support\TableTrait;
use Costa\LaravelPackage\Traits\Web\WebIndexTrait;
use Illuminate\Support\Str;

class ExtractController extends Controller
{
    use WebIndexTrait, TableTrait;

    protected function view(): string
    {
        return 'extract';
    }

    protected function service(): string
    {
        return ExtractService::class;
    }

    protected function getTableColumns(): array
    {
        return [
            __('Valor da transação') => fn($rs) => Str::numberEnToBr($rs->value_transfer),
            __('Tipo da Transação') => fn ($rs) => $rs->type,
            __('Origem da transação') => fn ($rs) => $rs->extract_type,
            __('Nome') => fn ($rs) => $rs->name,
            __('Resumo') => fn ($rs) => __($rs->resume, [
                'actual' => $rs->parcel
            ]),
        ];
    }
}
