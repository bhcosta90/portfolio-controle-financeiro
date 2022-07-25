<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class TenantSupport
{
    public function validate(Request $request)
    {
        if (Hash::check($request->tenant, $request->token)) {
            return $request->tenant;
        }

        abort(Response::HTTP_UNAUTHORIZED);
    }
}
