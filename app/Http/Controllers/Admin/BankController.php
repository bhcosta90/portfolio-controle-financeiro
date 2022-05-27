<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Forms\BankForm as Form;
use App\Http\Controllers\Presenters\PaginationPresenter;
use App\Support\FormSupport;

use Costa\Modules\Account\UseCases\Bank\{
    BankListUseCase as ListUseCase,
    BankCreateUseCase as CreateUseCase,
    BankDeleteUseCase as DeleteUseCase,
    BankFindUseCase as FindUseCase,
    BankUpdateUseCase as UpdateUseCase
};

use Costa\Modules\Account\UseCases\Bank\DTO\{
    List\Input as ListInput,
    Create\Input as CreateInput,
    Find\Input as FindInput,
    Update\Input as UpdateInput,
};

use Illuminate\Http\Request;

class BankController extends Controller
{
    private $routeRedirect = 'bank.index';
    
    public function index(ListUseCase $useCase, Request $request)
    {
        $result = $useCase->exec(new ListInput(
            filter: $request->all(),
            page: $request->page
        ));

        $data = PaginationPresenter::render($result->items, $result->total, $result->per_page, $result->current_page);

        return view('admin.bank.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        return view('admin.bank.create', [
            'form' => $formSupport->button('Cadastrar conta bancária')->run(
                Form::class,
                route('bank.store')
            )
        ]);
    }

    public function store(CreateUseCase $useCase, FormSupport $formSupport)
    {
        $data = $formSupport->data(Form::class);

        $model = $useCase->exec(new CreateInput(
            name: $data['name'],
            value: $data['value'],
            active: $data['active'] ?? true,
        ));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Conta bancária cadastrada com sucesso')
            ->with('model', $model);
    }

    public function edit(
        FormSupport $formSupport,
        FindUseCase $useCase,
        string $uuid
    ) {
        $model = $useCase->exec(new FindInput($uuid));

        return view('admin.bank.edit', [
            'form' => $formSupport->button('Editar conta bancária')->run(
                Form::class,
                route('bank.update', $model->id),
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
            name: $data['name'],
            value: $data['value'],
            active: $data['active'] ?? true,
        ));
        
        return redirect()->route($this->routeRedirect)
            ->with('success', 'Conta bancária editada com sucesso')
            ->with('model', $model);
    }

    public function destroy(
        DeleteUseCase $useCase,
        string $uuid
    ) {
        $useCase->exec(new FindInput($uuid));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Conta bancária deletada com sucesso');
    }
}
