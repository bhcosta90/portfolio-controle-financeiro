<?php

namespace App\Forms\Charge;

use App\Models\Income;
use App\Models\Parcel;
use Kris\LaravelFormBuilder\Form;

class ChargeForm extends Form
{
    use FormTrait;

    public function buildForm()
    {
        switch ($this->getData()['model']['chargeable_type']) {
            case Parcel::class;
                break;
            default:
                $this->name($this->getData()['model']['chargeable_type'] == Income::class ? 'Cliente' : 'Fornecedor');
                $this->resume();
                if ($this->getData()['model']['recurrency_id']) {
                    $this->add('updated_value', 'checkbox', [
                        'label' => __('Atualizar valor da recorrência')
                    ]);
                }
        }

        $this->value();
        $this->dueDate();
    }
}
