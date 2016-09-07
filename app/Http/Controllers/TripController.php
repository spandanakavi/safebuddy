<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return view('home');
    }
    
    
    /**
     * List active trips for admin and project manager
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id =1)
    {
        $trip =Trip::orderBy('id', 'desc')->take(10)->get();
        $tripDetails= array();
        
        foreach ($trip as $trip){
           $tripDetails[] =  $trip['attributes']; 
        }
        
        $tripDetails = ($trip->buildDetails($tripDetails));
//        echo '<pre>Trup'; print_r($tripDetails);

        return view('list', compact('tripDetails'));
    }
    

}
