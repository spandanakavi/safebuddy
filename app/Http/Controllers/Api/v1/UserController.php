<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\v1\Controller;
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
        $user = $this->fetchUser($id, $request);

        if ($user === null || empty($user)
            || (
                $user instanceof Illuminate\Database\Eloquent\Collection
                && $user->isEmpty()
            )
        ) {
            return $this->error(404, "User not found.");
        }

        return $user;
    }

    /**
     * Return the parent[s] of the user.
     */
    public function contacts($id, Request $request)
    {
        $user = $this->fetchUser($id, $request);

        if ($user === null || empty($user)
            || (
                $user instanceof Illuminate\Database\Eloquent\Collection
                && $user->isEmpty()
            )
        ) {
            return $this->error(404, "User not found.");
        }

        $contacts = $user->contacts();

        return $contacts;
    }

    private function fetchUser($id, Request $request)
    {
        $user = null;

        if ($id == "me") {

            // For current user
            $currentUserToken = $request->header('Authorization');
            $currentUserToken = substr($currentUserToken, strlen('Bearer '));

            $user = User::whereHas('accessToken', function($query) use($currentUserToken) {
                $query->where('id', '=', $currentUserToken);
            })->get();

        }
        else {

            // For others
            $user = User::find($id);
        }

        return $user;
    }


}
