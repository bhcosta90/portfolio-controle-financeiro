<?php

namespace App\Forms\Charge;

use Costa\Modules\Charge\Repository\RecurrenceRepositoryInterface;
use Costa\Modules\Relationship\Entities\CustomerEntity;
use Costa\Modules\Relationship\Repository\CustomerRepositoryInterface;
use Kris\LaravelFormBuilder\Form;

class ChargeReceiveForm extends Form
{
    public function __construct(
        protected CustomerRepositoryInterface $relationship,
        protected RecurrenceRepositoryInterface $recurrence,
    ) {
        //
    }

    public function buildForm()
    {
        $verify = $this->request->route('receive');

        $this->add('relationship_id', 'select', [
            'choices' => $data = $this->relationship->pluck([CustomerEntity::class]),
            'rules' => ['required', 'in:' . implode(',', array_keys($data))],
            'empty_value' => __('Selecione') . '...',
            'label' => __("Cliente"),
        ]);

        $this->add('title', 'text', [
            'rules' => ['required', 'min:0', 'max:150'],
            'label' => __('Descrição'),
        ]);

        $this->add('description', 'text', [
            'rules' => ['nullable', 'min:0', 'max:150'],
            'label' => __('Resumo'),
        ]);

        $this->add('recurrence_id', 'select', [
            'choices' => $data = $this->recurrence->pluck(),
            'rules' => ['nullable', 'in:' . implode(',', array_keys($data))],
            'empty_value' => __('Única vez'),
            'label' => __("Recorrência"),
        ]);

        $this->add('value', 'number', [
            'rules' => ['required', 'min:0', 'max:9999999999'],
            'label' => 'Valor da Cobrança',
            'attr' => [
                'step' => '0.01'
            ]
        ]);

        $this->add('date', 'date', [
            'rules' => ['required', 'date'],
            'label' => 'Vencimento',
            'value' => $this->model?->dueDate ?? date('Y-m-d')
        ]);

        if (empty($verify)) {
            $this->add('parcel', 'number', [
                'rules' => ['required', 'min:1', 'max:48'],
                'label' => 'Total de parcela',
                'value' => $this->model?->parcel ?? 1
            ]);
        }
    }
}
