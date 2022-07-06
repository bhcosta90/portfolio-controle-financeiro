<?php

namespace Core\Application\Payment\Services\Report;

use Core\Application\Payment\Repository\PaymentRepository;
use Core\Application\Payment\Shared\Enums\PaymentTypeEnum;
use Core\Application\Report\Contracts\PriceInterface;
use Core\Application\Report\Helper\GenerateReportHelper;

class PaymentReport
{
    public function __construct(
        private PaymentRepository $repository,
        private PriceInterface $price,
    ) {
        //
    }

    public function handle(DTO\Report\Header $header, DTO\Report\Input $input)
    {
        $report = GenerateReportHelper::handle($input->title, $input->type, $input->subtitle);

        $limit = 30;
        $page = 1;

        do {
            $result = $this->repository->report($input->filter, $page, $limit)->items();

            $report->column_text = $header->relationship;
            $report->column_alignment = 'left';
            $report->column_size = $column01 = 7;
            $report->addColumn();

            $report->column_text = $header->title;
            $report->column_alignment = 'left';
            $report->column_size = $column02 = 7;
            $report->addColumn();

            $report->column_text = $header->bank;
            $report->column_alignment = 'left';
            $report->column_size = $column03 = 7;
            $report->addColumn();

            $report->column_text = $header->value;
            $report->column_alignment = 'left';
            $report->column_size = $column04 = 7;
            $report->addColumn();

            $report->line_style = 'header';
            $report->addLine();
            $report->line_style = '';

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
                
                $minus = $rs->type == PaymentTypeEnum::DEBIT->value ? '-' : '';
                $report->column_text = $minus . $prefix . $this->price->convert($rs->value) . $bankValue;
                $report->column_alignment = 'left';
                $report->column_size = $column04;
                $report->addColumn();

                $report->line_style = '';
                $report->addLine();
            }

            $report->addPage();
            $page++;
        } while (count($result) === $limit);

        return $report->render();
    }
}
