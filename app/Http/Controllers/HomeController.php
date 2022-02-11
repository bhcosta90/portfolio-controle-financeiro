<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
    public function index(Request $request)
    {
        $token = $request->user()->getTokenRelatorio()->plainTextToken;
        $dateStart = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $dateEnd = Carbon::now()->lastOfMonth()->format('Y-m-d');

        return view('home', compact('token', 'dateStart', 'dateEnd'));
    }
}
