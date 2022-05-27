<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Forms\RecurrenceForm as Form;
use App\Http\Controllers\Presenters\PaginationPresenter;
use App\Support\FormSupport;

use Costa\Modules\Charge\UseCases\Recurrence\{
    RecurrenceListUseCase as ListUseCase,
    RecurrenceCreateUseCase as CreateUseCase,
    RecurrenceDeleteUseCase as DeleteUseCase,
    RecurrenceFindUseCase as FindUseCase,
    RecurrenceUpdateUseCase as UpdateUseCase
};

use Costa\Modules\Charge\UseCases\Recurrence\DTO\{
    List\Input as ListInput,
    Create\Input as CreateInput,
    Find\Input as FindInput,
    Update\Input as UpdateInput,
};
use Illuminate\Http\Request;

class RecurrenceController extends Controller
{
    private $routeRedirect = 'recurrence.index';
    
    public function index(ListUseCase $useCase, Request $request)
    {
        $result = $useCase->exec(new ListInput(
            filter: $request->all(),
            page: $request->page
        ));

        $data = PaginationPresenter::render($result->items, $result->total, $result->per_page, $result->current_page);

        return view('admin.recurrence.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        return view('admin.recurrence.create', [
            'form' => $formSupport->button('Cadastrar recorrência')->run(
                Form::class,
                route('recurrence.store')
            )
        ]);
    }

    public function store(CreateUseCase $useCase, FormSupport $formSupport)
    {
        $data = $formSupport->data(Form::class);

        $model = $useCase->exec(new CreateInput(
            name: $data['name'],
            days: $data['days']
        ));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Recorrência cadastrada com sucesso')
            ->with('model', $model);
    }

    public function edit(
        FormSupport $formSupport,
        FindUseCase $useCase,
        string $uuid
    ) {
        $model = $useCase->exec(new FindInput($uuid));

        return view('admin.recurrence.edit', [
            'form' => $formSupport->button('Editar recorrência')->run(
                Form::class,
                route('recurrence.update', $model->id),
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
            days: $data['days'],
        ));
        
        return redirect()->route($this->routeRedirect)
            ->with('success', 'Recorrência editada com sucesso')
            ->with('model', $model);
    }

    public function destroy(
        DeleteUseCase $useCase,
        string $uuid
    ) {
        $useCase->exec(new FindInput($uuid));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Recorrência deletada com sucesso');
    }
}
