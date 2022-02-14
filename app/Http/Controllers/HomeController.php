<?php

namespace App\Http\Controllers;

use App\Services\UserSharedService;
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

        $shareds = $this->getUserSharedService()->myPendentsShared($request->user()->email);

        return view('home', compact('token', 'dateStart', 'dateEnd', 'shareds'));
    }

    /**
     * @return UserSharedService
     */
    protected function getUserSharedService()
    {
        return app(UserSharedService::class);
    }
}
