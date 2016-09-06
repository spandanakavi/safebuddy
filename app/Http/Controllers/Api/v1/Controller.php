<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\User;

class Controller extends BaseController
{
    protected function error($code, $description)
    {
        return response()->json(['code' => $code, "message" => $description], $code);
    }

    protected function fetchUser($id, Request $request)
    {
        $user = null;

        if ($id == "me") {

            // For current user
            $currentUserToken = $request->header('Authorization');
            $currentUserToken = substr($currentUserToken, strlen('Bearer '));

            $user = User::whereHas('accessToken', function($query) use($currentUserToken) {
                $query->where('id', '=', $currentUserToken);
            })->get()->first();
        }
        else {

            // For others
            $user = User::find($id);
        }

        return $user;
    }
}
