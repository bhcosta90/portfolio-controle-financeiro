<?php

namespace App\Http\Controllers;

use App\Services\ResumoService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function resumo($tipo)
    {
        $service = app(ResumoService::class);
        $data = $service->$tipo();
        $dataFormatada = [];

        foreach ($data as $k => $v) {
            $dataFormatada[] = [
                'key' => $k,
                'value' => $v,
            ];
        }

        return [
            'data' => $dataFormatada,
            'tipo' => $tipo,
        ];
    }
}
