<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('user.profile', [
            'model' => $request->user()->with('tenants')->first(),
        ]);
    }

    public function profile(UpdateProfile $request)
    {
        $request->user()->update([
            'name' => $request->name,
            'email' => $request->email,
            'tenant_id' => $request->company_id,
        ]);
        return redirect()->back()->with('success', __('Profile update successfully'));
    }
}
