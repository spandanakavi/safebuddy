<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Api\v1\Controller;
use App\User;
use App\Tracking;

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

    /**
     * Track the current user.
     */
    public function track(Request $request)
    {
        $user = $this->fetchUser("me", $request);
        if ($user === null || empty($user)
            || (
                $user instanceof Illuminate\Database\Eloquent\Collection
                && $user->isEmpty()
            )
        ) {
            return $this->error(404, "User not found.");
        }

        $tracking = $this->trackUser($user, $request);
        if (!$tracking) {
            return $this->error(
                422, sprintf(
                    "Unable to track user.",
                    $user->first_name . ' ' . $user->last_name
                )
            );
        }

        return $tracking;
    }

    /**
     * Track the current user.
     */
    public function sos(Request $request)
    {
        $user = $this->fetchUser("me", $request);
        if ($user === null || empty($user)
            || (
                $user instanceof Illuminate\Database\Eloquent\Collection
                && $user->isEmpty()
            )
        ) {
            return $this->error(404, "User not found.");
        }

        $tracking = $this->trackUser($user, $request, true);
        if (!$tracking) {
            return $this->error(
                422, sprintf(
                    "Unable to track user.",
                    $user->first_name . ' ' . $user->last_name
                )
            );
        }

        return $tracking;
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
            })->get()->first();

        }
        else {

            // For others
            $user = User::find($id);
        }

        return $user;
    }

    private function trackUser($user, Request $request, $isSos = false)
    {
        $tracking = new Tracking();
        $tracking->user_id = $user->id;
        $tracking->trip_id = $request['trip_id'];
        $tracking->current_time = Carbon::now();
        $tracking->lat = $request['lat'];
        $tracking->lng = $request['lng'];
        $tracking->is_sos = $isSos;

        $saved = $tracking->save();
        if ($saved) {
            return $tracking;
        }
        return false;
    }
}
