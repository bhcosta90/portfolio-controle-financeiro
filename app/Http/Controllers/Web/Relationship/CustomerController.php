<?php

namespace App\Http\Controllers\Web\Relationship;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Presenters\PaginationPresenter;
use App\Support\FormSupport;
use App\Forms\CustomerForm as Form;
use Costa\Modules\Relationship\Customer\UseCases\CreateUseCase;
use Costa\Modules\Relationship\Customer\UseCases\DeleteUseCase;
use Costa\Modules\Relationship\Customer\UseCases\ListUseCase;
use Costa\Modules\Relationship\Customer\UseCases\DTO\List\Input as ListInput;
use Costa\Modules\Relationship\Customer\UseCases\DTO\Find\Input as FindInput;
use Costa\Modules\Relationship\Customer\UseCases\DTO\Update\Input as UpdateInput;
use Costa\Modules\Relationship\Customer\UseCases\DTO\Create\Input as CreateInput;
use Costa\Modules\Relationship\Customer\UseCases\FindUseCase;
use Costa\Modules\Relationship\Customer\UseCases\UpdateUseCase;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private $routeRedirect = 'admin.customer.index';
    
    public function index(ListUseCase $uc, Request $request)
    {
        $ret = $uc->exec(new ListInput(
            $request->all(), 
            $request->limit,
            $request->page,
        ));

        $data = PaginationPresenter::render($ret);

        return view('admin.customer.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        return view('admin.customer.create', [
            'form' => $formSupport->button('Cadastrar cliente')->run(
                Form::class,
                route('admin.customer.store')
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
            ->with('success', 'Cliente cadastrado com sucesso')
            ->with('model', $model);
    }

    public function edit(
        FormSupport $formSupport,
        FindUseCase $useCase,
        string $id
    ) {
        $model = $useCase->handle(new FindInput($id));

        return view('admin.customer.edit', [
            'form' => $formSupport->button('Editar cliente')->run(
                Form::class,
                route('admin.customer.update', $model->id),
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
            ->with('success', 'Cliente editado com sucesso')
            ->with('model', $model);
    }

    public function destroy(
        DeleteUseCase $useCase,
        string $id
    ) {
        $useCase->handle(new FindInput($id));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Cliente deletado com sucesso');
    }
}
