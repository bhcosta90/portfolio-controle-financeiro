<?php

namespace App\Http\Controllers\Web\Relationship;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Presenters\PaginationPresenter;
use App\Support\FormSupport;
use App\Forms\SupplierForm as Form;
use Costa\Modules\Relationship\Supplier\UseCases\CreateUseCase;
use Costa\Modules\Relationship\Supplier\UseCases\DeleteUseCase;
use Costa\Modules\Relationship\Supplier\UseCases\ListUseCase;
use Costa\Modules\Relationship\Supplier\UseCases\DTO\List\Input as ListInput;
use Costa\Modules\Relationship\Supplier\UseCases\DTO\Find\Input as FindInput;
use Costa\Modules\Relationship\Supplier\UseCases\DTO\Update\Input as UpdateInput;
use Costa\Modules\Relationship\Supplier\UseCases\DTO\Create\Input as CreateInput;
use Costa\Modules\Relationship\Supplier\UseCases\FindUseCase;
use Costa\Modules\Relationship\Supplier\UseCases\UpdateUseCase;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    private $routeRedirect = 'admin.supplier.index';
    
    public function index(ListUseCase $uc, Request $request)
    {
        $ret = $uc->exec(new ListInput(
            $request->all(), 
            $request->limit,
            $request->page,
        ));

        $data = PaginationPresenter::render($ret);

        return view('admin.supplier.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        return view('admin.supplier.create', [
            'form' => $formSupport->button('Cadastrar fornecedor')->run(
                Form::class,
                route('admin.supplier.store')
            )
        ]);
    }

    public function store(CreateUseCase $useCase, FormSupport $formSupport)
    {
        $data = $formSupport->data(Form::class);
        $model = $useCase->handle(new CreateInput(
            name: $data['name']
        ));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Fornecedor cadastrado com sucesso')
            ->with('model', $model);
    }

    public function edit(
        FormSupport $formSupport,
        FindUseCase $useCase,
        string $id
    ) {
        $model = $useCase->handle(new FindInput($id));

        return view('admin.supplier.edit', [
            'form' => $formSupport->button('Editar fornecedor')->run(
                Form::class,
                route('admin.supplier.update', $model->id),
                $model,
            )
        ]);
    }

    public function update(
        FormSupport $formSupport,
        UpdateUseCase $useCase,
        string $id
    ) {
        $data = $formSupport->data(Form::class);

        $model = $useCase->handle(new UpdateInput(
            id: $id,
            name: $data['name']
        ));
        
        return redirect()->route($this->routeRedirect)
            ->with('success', 'Fornecedor editado com sucesso')
            ->with('model', $model);
    }

    public function destroy(
        DeleteUseCase $useCase,
        string $id
    ) {
        $useCase->handle(new FindInput($id));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Fornecedor deletado com sucesso');
    }
}
