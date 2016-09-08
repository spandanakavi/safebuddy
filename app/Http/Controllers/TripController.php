<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Trip;
use App\User;



class TripController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $signedUser = Auth::user();        
        $tripDetails = $this->recentTrips('id', 'desc', '10');

        return view('list', compact('tripDetails'));
    }

    public function recentTrips($id, $order, $count)
    {
        $signedUser = Auth::user();
        $isAdmin = $signedUser->is_admin;

        $projectsIds = array();
        $tripDetails = array();

        $trip = Trip::orderBy('id', 'desc')->take($count)->get();

        foreach ($trip as $trip) {
            $tripDetails[] = $trip['attributes'];
        }

        $projects = $trip->projects($signedUser->id);

        foreach ($projects as $project) {
            $projectsIds[] = $project->id;
        }

        $tripDetails = ($trip->buildDetails($tripDetails, $isAdmin, $projectsIds));

        return $tripDetails;
    }

}
