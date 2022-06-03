<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\Presenters\PaginationPresenter;
use App\Support\FormSupport;
use App\Forms\RecurrenceForm as Form;
use Costa\Modules\Recurrence\UseCases\CreateUseCase;
use Costa\Modules\Recurrence\UseCases\DeleteUseCase;
use Costa\Modules\Recurrence\UseCases\ListUseCase;
use Costa\Modules\Recurrence\UseCases\DTO\List\Input as ListInput;
use Costa\Modules\Recurrence\UseCases\DTO\Find\Input as FindInput;
use Costa\Modules\Recurrence\UseCases\DTO\Update\Input as UpdateInput;
use Costa\Modules\Recurrence\UseCases\DTO\Create\Input as CreateInput;
use Costa\Modules\Recurrence\UseCases\FindUseCase;
use Costa\Modules\Recurrence\UseCases\UpdateUseCase;
use Illuminate\Http\Request;

class RecurrenceController extends Controller
{
    private $routeRedirect = 'admin.recurrence.index';
    
    public function index(ListUseCase $uc, Request $request)
    {
        $ret = $uc->exec(new ListInput(
            $request->all(), 
            $request->limit,
            $request->page,
        ));

        $data = PaginationPresenter::render($ret);

        return view('admin.recurrence.index', compact('data'));
    }

    public function create(FormSupport $formSupport)
    {
        return view('admin.recurrence.create', [
            'form' => $formSupport->button('Cadastrar recorrência')->run(
                Form::class,
                route('admin.recurrence.store')
            )
        ]);
    }

    public function store(CreateUseCase $useCase, FormSupport $formSupport)
    {
        $data = $formSupport->data(Form::class);
        $model = $useCase->handle(new CreateInput(
            name: $data['name'],
            days: $data['days'],
        ));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Recorrência cadastrado com sucesso')
            ->with('model', $model);
    }

    public function edit(
        FormSupport $formSupport,
        FindUseCase $useCase,
        string $id
    ) {
        $model = $useCase->handle(new FindInput($id));

        return view('admin.recurrence.edit', [
            'form' => $formSupport->button('Editar recorrência')->run(
                Form::class,
                route('admin.recurrence.update', $model->id),
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
            name: $data['name'],
            days: $data['days'],
        ));
        
        return redirect()->route($this->routeRedirect)
            ->with('success', 'Recorrência editado com sucesso')
            ->with('model', $model);
    }

    public function destroy(
        DeleteUseCase $useCase,
        string $id
    ) {
        $useCase->handle(new FindInput($id));

        return redirect()->route($this->routeRedirect)
            ->with('success', 'Recorrência deletado com sucesso');
    }
}
