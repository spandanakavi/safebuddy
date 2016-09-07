<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ManagerController extends Controller
{
    public function index()
    {
        return view('manager.home');
    }
    public function show()
    {
        return view('detail');
    }
}
