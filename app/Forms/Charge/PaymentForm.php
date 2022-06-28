<?php

namespace App\Forms\Charge;

use Core\Financial\Recurrence\Repository\RecurrenceRepositoryInterface;
use Core\Financial\Relationship\Modules\Company\Repository\CompanyRepositoryInterface;
use Kris\LaravelFormBuilder\Form;

class PaymentForm extends Form
{
    public function __construct(
        private CompanyRepositoryInterface $relationship,
        private RecurrenceRepositoryInterface $recurrence,
    ) {
        //
    }

    public function buildForm()
    {
        $this->add('relationship_id', 'select', [
            'label' => __('Cliente'),
            'choices' => $pluck = $this->relationship->pluck(),
            'empty_value' => __('Selecione...'),
            'rules' => ['required', 'in:' . implode(',', array_keys($pluck))],
        ]);

        $this->add('value', 'number', [
            'rules' => ['required', 'numeric', 'min:0.01'],
            'label' => __('Valor'),
            'attr' => ['step' => '0.01']
        ]);

        $this->add('recurrence_id', 'select', [
            'label' => __('Recorrência'),
            'choices' => $pluck = $this->recurrence->pluck(),
            'empty_value' => __('Selecione...'),
            'rules' => ['nullable', 'in:' . implode(',', array_keys($pluck))],
        ]);

        $this->add('date', 'date', [
            'rules' => ['required', 'date'],
            'label' => __('Data Vencimento'),
            'value' => $this->model?->date ?? date('Y-m-d')
        ]);

        $this->add('parcel', 'select', [
            'choices' => $pluck = $this->getParcels(),
            'rules' => ['nullable', 'in:' . implode(',', array_keys($pluck))],
            'empty_value' => __('A vista'),
            'label' => __('Total de Parcela'),
        ]);
    }

    private function getParcels()
    {
        $ret = [];

        for ($i = 2; $i < 12; $i++) {
            $ret[$i] = str_pad($i, 2, '0', STR_PAD_LEFT) . 'x';
        }

        while ($i <= 48) {
            $ret[$i] = str_pad($i, 2, '0', STR_PAD_LEFT) . 'x';
            $i += 6;
        }

        return $ret;
    }
}
