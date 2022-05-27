<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\FormSupport;
use Illuminate\Http\Request;
use App\Forms\SupplierForm as Form;
use App\Http\Controllers\Presenters\PaginationPresenter;
use Costa\Modules\Relationship\UseCases\Supplier\{
    SupplierListUseCase as ListUseCase,
    SupplierCreateUseCase as CreateUseCase,
    SupplierDeleteUseCase as DeleteUseCase,
    SupplierFindUseCase as FindUseCase,
    SupplierUpdateUseCase as UpdateUseCase
};

use Costa\Modules\Relationship\UseCases\Supplier\DTO\{
    List\Input as ListInput,
    Create\Input as CreateInput,
    Find\Input as FindInput,
    Update\Input as UpdateInput,
};

class SupplierController extends Controller
{
    private $routeRedirect = 'supplier.index';

    public function index(ListUseCase $useCase, Request $request)
    {
        $result = $useCase->exec(new ListInput(
            filter: $request->all(),
            page: $request->page,
        ));

        $data = PaginationPresenter::render($result->items, $result->total, $result->per_page, $result->current_page);

        return view('admin.supplier.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        return view('admin.supplier.create', [
            'form' => $formSupport->button('Cadastrar fornecedor')->run(
                Form::class,
                route('supplier.store')
            )
        ]);
    }

    public function store(CreateUseCase $useCase, FormSupport $formSupport)
    {
        $data = $formSupport->data(Form::class);
        $model = $useCase->exec(new CreateInput(
            name: $data['name']
        ));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Fornecedor cadastrado com sucesso')
            ->with('model', $model);
    }

    public function edit(
        FormSupport $formSupport,
        FindUseCase $useCase,
        string $uuid
    ) {
        $model = $useCase->exec(new FindInput($uuid));

        return view('admin.supplier.edit', [
            'form' => $formSupport->button('Editar fornecedor')->run(
                Form::class,
                route('supplier.update', $model->id),
                $model,
            )
        ]);
    }

    public function update(
        FormSupport $formSupport,
        UpdateUseCase $useCase,
        string $uuid
    ) {
        $data = $formSupport->data(Form::class);

        $model = $useCase->exec(new UpdateInput(
            id: $uuid,
            name: $data['name']
        ));
        
        return redirect()->route($this->routeRedirect)
            ->with('success', 'Fornecedor editado com sucesso')
            ->with('model', $model);
    }

    public function destroy(
        DeleteUseCase $useCase,
        string $uuid
    ) {
        $useCase->exec(new FindInput($uuid));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Fornecedor deletado com sucesso');
    }
}
