<?php

namespace App\Http\Controllers\Admin\Web\Relationship;

use App\Forms\Relationship\CustomerForm as Form;
use App\Http\Controllers\Admin\Presenters\PaginationPresenter;
use App\Http\Controllers\Controller;
use App\Support\FormSupport;
use Core\Application\Relationship\Modules\Customer\Services;
use Core\Shared\UseCases\Find\FindInput;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Services\ListService $listService, Request $request)
    {
        $result = $listService->handle(new ListInput(filter: $request->all(), page: $request->page));
        $data = PaginationPresenter::render($result);
        return view('admin.relationship.customer.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        $form = $formSupport->button(__('Cadastrar Cliente'))
            ->run(Form::class, route('admin.relationship.customer.store'));
        return view('admin.relationship.customer.create', compact('form'));
    }

    public function store(FormSupport $formSupport, Services\CreateService $createService, Request $request)
    {
        $data = $formSupport->data(Form::class);
        $ret = $createService->handle(new Services\DTO\Create\Input($request->user()->tenant_id, $data['name']));
        return redirect()->route('admin.relationship.customer.index')
            ->with('success', __('Cliente cadastrada com sucesso'))
            ->with('service', $ret);
    }

    public function edit(FormSupport $formSupport, Services\FindService $findService, string $id)
    {
        $model = $findService->handle(new FindInput($id));
        $form = $formSupport->button(__('Cadastrar Cliente'))
            ->run(Form::class, route('admin.relationship.customer.update', $id), $model);

        return view('admin.relationship.customer.edit', compact('form'));
    }

    public function update(FormSupport $formSupport, Services\UpdateService $createService, string $id)
    {
        $data = $formSupport->data(Form::class);
        $ret = $createService->handle(new Services\DTO\Update\Input($id, $data['name']));
        return redirect()->route('admin.relationship.customer.index')
            ->with('success', __('Cliente editada com sucesso'))
            ->with('service', $ret);
    }
}
