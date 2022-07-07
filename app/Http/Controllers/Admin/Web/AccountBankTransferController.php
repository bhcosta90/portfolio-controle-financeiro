<?php

namespace App\Http\Controllers\Admin\Web;

use App\Forms\AccountBankTransferForm;
use App\Http\Controllers\Controller;
use App\Support\FormSupport;
use Core\Application\AccountBank\Services;
use Illuminate\Http\Request;

class AccountBankTransferController extends Controller
{
    public function create(string $account, FormSupport $formSupport)
    {
        $form = $formSupport->button(__('Transferir entre contas'))
            ->add(['remove_id_account' => [$account]])
            ->run(AccountBankTransferForm::class, route('admin.bank.account.transfer.store', $account));

        return view('admin.bank.account.transfer.create', compact('form'));
    }

    public function store(
        string $account,
        FormSupport $formSupport,
        Services\TransferService $transfer,
        Request $request
    )
    {
        $data = $formSupport->add(['remove_id_account' => [$account]])->data(AccountBankTransferForm::class);
        $ret = $transfer->handle(new Services\DTO\Transfer\Input(
            tenant: $request->user()->tenant_id,
            idBankFrom: $account,
            idBankTo: $data['bank_account_id'],
            value: $data['value']
        ));

        return redirect()->route('admin.payment.index')
            ->with('success', __('Transferência executada com sucesso, aguarde o processamento'))
            ->with('service', $ret);        
    }
}
