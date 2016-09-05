<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\v1\Controller;
use App\Buddy;

class BuddyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Buddy::all();
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
        $buddy = new Buddy();

        if ($this->save($request, $buddy)) {
            return $buddy;
        }

        return $this->error(422, "Unable to save buddy.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $buddy = Buddy::find($id);
        if ($buddy === null || empty($buddy)) {
            return $this->error(404, "Project not found.");
        }

        return $buddy;
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
        $buddy = Buddy::find($id);
        if ($buddy === null || empty($buddy)) {
            return $this->error(404, "Project not found.");
        }

        if ($this->save($request, $buddy)) {
            return $buddy;
        }

        return $this->error(422, "Unable to save buddy.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $count = Buddy::destroy($id);

        if ($count <= 0) {
            return $this->error(404, 'Buddy not found.');
        }
    }

    private function save(Request $request, Buddy $buddy)
    {
        $buddy->first_name = $request->input('first_name');
        $buddy->last_name = $request->input('last_name');
        $buddy->email = $request->input('email');
        $buddy->mobile = $request->input('mobile');
        $buddy->buddy_of_user_id = $request->input('buddy_of_user_id');

        return $buddy->save();
    }
}
