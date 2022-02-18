<?php

namespace Modules\Cobranca\Forms;

use Kris\LaravelFormBuilder\Form;

class FrequenciaForm extends Form
{
    public function buildForm()
    {
        $this->add('nome', 'text', [
            'label' => 'Nome',
            'rules' => ['required'],
        ]);

        $tipos = $this->getData()['model'] ? explode('|', $this->getData()['model']->tipo) : false;

        if ($tipos === false || count($tipos) > 1) {
            $this->add('tipo', 'number', [
                'label' => 'Quantidade de dias',
                'rules' => ['required', 'min:1', 'max:365'],
                'value' => $tipos[0] ?? null
            ]);
        }

        $this->add('ordem_frequencia', 'number', [
            'label' => 'Ordem de frequência',
            'rules' => ['required', 'numeric'],
        ]);

        $this->add('ordem_parcela', 'number', [
            'label' => 'Ordem para parcelamento',
            'rules' => ['required', 'numeric'],
        ]);

        $this->add('ativo', 'select', [
            'label' => 'Ativo',
            'choices' => [
                1 => 'Sim',
                0 => 'Não'
            ],
        ]);
    }
}
