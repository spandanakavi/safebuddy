<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\User;
use Lcobucci\JWT\Parser;

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
            $currentUserJwtToken = $request->header('Authorization');
            $currentUserJwtToken = substr($currentUserJwtToken, strlen('Bearer '));
            $parser = new Parser();
            $token = $parser->parse((string) $currentUserJwtToken);
            $currentUserToken = $token->getHeader('jti');
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
