<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Return the user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        // For current user
        if ($id == "me") {
            $currentUserToken = $request->header('Authorization');
            $currentUserToken = substr($currentUserToken, strlen('Bearer '));

            return User::whereHas('accessToken', function($query) use($currentUserToken) {
                $query->where('id', '=', $currentUserToken);
            })->get();
        }

        // For others
        return User::find($id);
    }
}
