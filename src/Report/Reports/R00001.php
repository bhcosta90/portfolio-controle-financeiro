<?php

namespace Core\Report\Reports;

use Core\Application\Transaction\Repository\TransactionRepository;
use Core\Application\Transaction\Shared\Enums\TransactionTypeEnum;
use Core\Report\Contracts\PriceInterface;
use Core\Report\Type\Abstracts\ReportTypeAbstract;
use DateTime;

class R00001
{
    public function __construct(
        private TransactionRepository $repository,
        private PriceInterface $price,
    )
    {
        //
    }

    public function render_a(ReportTypeAbstract $report)
    {
        $limit = 30;
        $page = 1;
        $report->title = 'Relatório de Pagamento';

        $dateStart = new DateTime($_GET['date'][0] ?? '');
        $dateFinish = new DateTime($_GET['date'][1] ?? '');

        if (empty($_GET['date'][0])) {
            $dateStart->modify('first day of this month');
        }

        if (empty($_GET['date'][1])) {
            $dateFinish->modify('last day of this month');
        }

        do {
            $repository = clone $this->repository;

            $repository->filterByDate(
                $dateStart->setTime(0, 0, 0),
                $dateFinish->setTime(23, 59, 59)
            );
            
            if (!empty($_GET['customer_name'])) {
                $repository->filterByName($_GET['customer_name']);
            }

            $result = $this->repository->report($page, $limit)->items();

            $report->column_text = 'Cliente / Fornecedor';
            $report->column_alignment = 'left';
            $report->column_size = $column01 = 7;
            $report->addColumn();

            $report->column_text = 'Título';
            $report->column_alignment = 'left';
            $report->column_size = $column02 = 7;
            $report->addColumn();

            $report->column_text = 'Banco';
            $report->column_alignment = 'left';
            $report->column_size = $column03 = 7;
            $report->addColumn();

            $report->column_text = 'Valor';
            $report->column_alignment = 'left';
            $report->column_size = $column04 = 7;
            $report->addColumn();

            $report->line_style = 'header';
            $report->addLine();
            $report->line_style = '';
            $report = $this->executeForeachRenderA($report, $result, $column01, $column02, $column03, $column04);
            $report->addPage();
            $page++;
        } while (count($result) === $limit);
    }

    private function executeForeachRenderA($report, $result, $column01, $column02, $column03, $column04)
    {
        foreach ($result as $rs) {
            $report->column_text = $rs->relationship_name ?: '-';
            $report->column_alignment = 'left';
            $report->column_size = $column01;
            $report->addColumn();

            $report->column_text = $rs->title;
            $report->column_alignment = 'left';
            $report->column_size = $column02;
            $report->addColumn();

            $bank = $rs->bank_name;
            $report->column_text = $bank ?: "-";
            $report->column_alignment = 'left';
            $report->column_size = $column03;
            $report->addColumn();

            $bankValue = "";
            $prefix = ($prefix = $this->price->prefix()) ? "{$prefix} " : "";

            if ($bank && $rs->value_bank) {
                $bankValue .= ' <small>(' . $prefix . $this->price->convert($rs->value_bank) . ')</small>';
            }

            $minus = $rs->type == TransactionTypeEnum::DEBIT->value ? '-' : '';
            $report->column_text = $minus . $prefix . $this->price->convert($rs->value) . $bankValue;
            $report->column_alignment = 'left';
            $report->column_size = $column04;
            $report->addColumn();

            $report->line_style = '';
            $report->addLine();
        }

        return $report;
    }
}
