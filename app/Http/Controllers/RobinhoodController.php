<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class RobinhoodController extends Controller
{
    public function receiveMfaCode(Request $request, User $user)
    {
        $code = $request->code;
        if ($code) {
            return response($user->mfaCode()->create(['code' => $code]), 200);
        }

        return response('Code not sent.', 400);
    }
}
