<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\v1\Controller;
use App\Trip;
use Carbon\Carbon;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Trip::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $trip = new Trip();

        if ($this->save($request, $trip)) {
            return $trip;
        }

        return $this->error(422, "Unable to save trip.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Trip::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $trip = Trip::find($id);
        if ($trip === null || empty($trip)) {
            return $this->error(404, "Trip not found.");
        }

        if ($this->save($request, $trip)) {
            return $trip;
        }

        return $this->error(422, "Unable to save trip.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function save(Request $request, Trip $trip)
    {

        if (!$trip->exists) {
            // New trip started
            $user = $this->fetchUser("me", $request);

            $trip->user_id = $user->id;
            $trip->start_time = Carbon::now();
            $trip->vehicle_id = $request->input('vehicle_id');
            $trip->source = $request->input('source');
            $trip->destination = $request->input('destination');
        }
        else {
            // Trip ended
            $trip->end_time = Carbon::now();
        }

        return $trip->save();
    }
}
