<?php

namespace App\Http\Controllers\Admin\Web\Relationship;

use App\Forms\Relationship\CompanyForm as Form;
use App\Http\Controllers\Admin\Presenters\PaginationPresenter;
use App\Http\Controllers\Controller;
use App\Support\FormSupport;
use Core\Application\Relationship\Modules\Company\Services;
use Core\Shared\UseCases\Delete\DeleteInput;
use Core\Shared\UseCases\Find\FindInput;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Services\ListService $listService, Request $request)
    {
        $result = $listService->handle(new ListInput(filter: $request->all(), page: $request->page));
        $data = PaginationPresenter::render($result);
        return view('admin.relationship.company.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        $form = $formSupport->button(__('Cadastrar Empresa'))
            ->run(Form::class, route('admin.relationship.company.store'));
        return view('admin.relationship.company.create', compact('form'));
    }

    public function store(FormSupport $formSupport, Services\CreateService $createService, Request $request)
    {
        $data = $formSupport->data(Form::class);
        $ret = $createService->handle(new Services\DTO\Create\Input($request->user()->tenant_id, $data['name']));
        return redirect()->route('admin.relationship.company.index')
            ->with('success', __('Empresa cadastrada com sucesso'))
            ->with('service', $ret);
    }

    public function edit(FormSupport $formSupport, Services\FindService $findService, string $id)
    {
        $model = $findService->handle(new FindInput($id));
        $form = $formSupport->button(__('Cadastrar Empresa'))
            ->run(Form::class, route('admin.relationship.company.update', $id), $model);

        return view('admin.relationship.company.edit', compact('form'));
    }

    public function update(FormSupport $formSupport, Services\UpdateService $createService, string $id)
    {
        $data = $formSupport->data(Form::class);
        $ret = $createService->handle(new Services\DTO\Update\Input($id, $data['name']));
        return redirect()->route('admin.relationship.company.index')
            ->with('success', __('Empresa editada com sucesso'))
            ->with('service', $ret);
    }

    public function destroy(Services\DeleteService $deleteService, string $id)
    {
        $ret = $deleteService->handle(new DeleteInput($id));
        return redirect()->back()
            ->with('success', __('Empresa excluída com sucesso'))
            ->with('service', $ret);
    }
}
