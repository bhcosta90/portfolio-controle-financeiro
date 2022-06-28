<?php

namespace App\Http\Controllers\Admin\Web\Charge;

use App\Http\Controllers\Controller;
use App\Support\FormSupport;
use App\Forms\Charge\ReceiveForm as Form;
use Illuminate\Http\Request;

class ReceiveController extends Controller
{
    public function create(FormSupport $formSupport)
    {
        $form = $formSupport
            ->button(__('Cadastrar'))
            ->run(Form::class, route('admin.charge.receive.store'));

        return view('admin.charge.receive.create', compact('form'));
    }
}
