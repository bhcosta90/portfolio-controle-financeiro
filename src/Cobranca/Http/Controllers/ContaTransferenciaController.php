<?php

namespace Modules\Cobranca\Http\Controllers;

use Costa\LaravelPackage\Support\FormSupport;
use Costa\LaravelPackage\Traits\Support\ServiceTrait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Cobranca\Forms\ContaTransferenciaForm;
use Modules\Cobranca\Services\ContaBancariaService;
use Modules\Cobranca\Services\ContaTransferenciaService;

class ContaTransferenciaController extends Controller
{
    use ServiceTrait;

    public function create(FormSupport $formSupport)
    {
        $formSupport->form = ContaTransferenciaForm::class;
        $formSupport->button = 'Transferir';

        $form = $formSupport->exec('POST', route('cobranca.conta.transferencia.store', tenant()));

        return view('cobranca::contatransferencia.create', compact('form'));
    }

    public function store(FormSupport $formSupport)
    {
        $formSupport->form = ContaTransferenciaForm::class;
        $data = $formSupport->data();

        if ($data['conta_origem'] == $data['conta_destino']) {
            throw ValidationException::withMessages([
                'conta_origem' => 'Conta origem não pode ser igual a conta de destino',
                'conta_destino' => 'Conta destino não pode ser igual a conta de origem'
            ]);
        }

        $data['conta_origem'] = $this->getContaBancariaService()->find($data['conta_origem'])->id;
        $data['conta_destino'] = $this->getContaBancariaService()->find($data['conta_destino'])->id;
        $data['valor_transferencia'] = str()->numberBrToEn($data['valor_transferencia']);

        return DB::transaction(function () use ($data) {
            $this->getService()->store(auth()->user()->id, $data['conta_origem'], $data['conta_destino'], $data['valor_transferencia']);
            return redirect(route('cobranca.conta.transferencia.create', tenant()))->with('success', 'Trasnferência feita com sucesso');
        });
    }

    /**
     * @return ContaTransferenciaService
     */
    protected function service(): string
    {
        return ContaTransferenciaService::class;
    }

    /**
     * @return ContaBancariaService
     */
    private function getContaBancariaService()
    {
        return app(ContaBancariaService::class);
    }
}
