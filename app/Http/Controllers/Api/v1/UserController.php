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

    public function child(Request $request)
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

        $child = $user->child();
        if ($child === null || empty($child)
            || (
                $child instanceof Illuminate\Database\Eloquent\Collection
                && $child->isEmpty()
            )
        ) {
            return $this->error(404, "Child not found.");
        }

        return $child;
    }

    public function currentTrip($id, Request $request)
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

        $trip = $user->currentOrLatestTrip();
        if ($trip === null || empty($trip)
            || (
                $trip instanceof Illuminate\Database\Eloquent\Collection
                && $trip->isEmpty()
            )
        ) {
            return $this->error(404, "Current trip not found.");
        }

        return $trip;
    }

    public function trackings($id, Request $request)
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
        $tripId = $request['trip_id'];
        $trackings = $user->trackings($tripId);
        if ($trackings === null || empty($trackings)
            || (
                $trackings instanceof Illuminate\Database\Eloquent\Collection
                && $trackings->isEmpty()
            )
        ) {
            return $this->error(404, "Tracking entries not found.");
        }

        return $trackings;
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

    private function trackUser($user, Request $request, $isSos = false)
    {
        $tracking = new Tracking();
        $tracking->user_id = $user->id;
        $tracking->trip_id = $request['trip_id'];
        $tracking->current_time = $request['current_time'];
        $tracking->kmph = isset($request['kmph']) ? $request['kmph'] : null;
        $tracking->lat = $request['lat'];
        $tracking->lng = $request['lng'];
        $tracking->is_sos = $isSos;

        $saved = $tracking->save();
        if ($saved) {
            $tracking->publishToQueue();
            return $tracking;
        }
        return false;
    }
}
