<?php

namespace App\Http\Controllers\Admin\Web\Relationship;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Web\Presenters\PaginationPresenter;
use App\Http\Requests\Relationship\CompanyRequest;
use Core\Financial\Relationship\Modules\Company\UseCases\CreateUseCase;
use Core\Financial\Relationship\Modules\Company\UseCases\DTO\Create\CreateInput;
use Core\Financial\Relationship\Modules\Company\UseCases\ListUseCase;
use Core\Shared\UseCases\List\ListInput;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(ListUseCase $listUseCase, Request $request){
        $ret = $listUseCase->handle(new ListInput(filter: $request->all()));
        $data = PaginationPresenter::render($ret);
        dump($data);
    }

    public function create(CreateUseCase $createUseCase, CompanyRequest $request){
        $createUseCase->handle(new CreateInput(
            name: $request->name,
            document_type: $request->document_type,
            document_value: $request->document_value,
        ));
    }
}
