<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\FormSupport;
use Illuminate\Http\Request;
use App\Forms\SupplierForm as Form;
use App\Http\Controllers\Presenters\PaginationPresenter;
use Costa\Modules\Relationship\UseCases\Customer\{
    CustomerListUseCase as ListUseCase,
    CustomerCreateUseCase as CreateUseCase,
    CustomerDeleteUseCase as DeleteUseCase,
    CustomerFindUseCase as FindUseCase,
    CustomerUpdateUseCase as UpdateUseCase
};

use Costa\Modules\Relationship\UseCases\Customer\DTO\{
    List\Input as ListInput,
    Create\Input as CreateInput,
    Find\Input as FindInput,
    Update\Input as UpdateInput,
};

class CustomerController extends Controller
{
    private $routeRedirect = 'customer.index';

    public function index(ListUseCase $useCase, Request $request)
    {
        $result = $useCase->exec(new ListInput(
            filter: $request->all(),
            page: $request->page,
        ));

        $data = PaginationPresenter::render($result->items, $result->total, $result->per_page, $result->current_page);

        return view('admin.customer.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        return view('admin.customer.create', [
            'form' => $formSupport->button('Cadastrar cliente')->run(
                Form::class,
                route('customer.store')
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
            ->with('success', 'Cliente cadastrado com sucesso')
            ->with('model', $model);
    }

    public function edit(
        FormSupport $formSupport,
        FindUseCase $useCase,
        string $uuid
    ) {
        $model = $useCase->exec(new FindInput($uuid));

        return view('admin.customer.edit', [
            'form' => $formSupport->button('Editar cliente')->run(
                Form::class,
                route('customer.update', $model->id),
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
            ->with('success', 'Cliente editado com sucesso')
            ->with('model', $model);
    }

    public function destroy(
        DeleteUseCase $useCase,
        string $uuid
    ) {
        $useCase->exec(new FindInput($uuid));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Cliente deletado com sucesso');
    }
}
