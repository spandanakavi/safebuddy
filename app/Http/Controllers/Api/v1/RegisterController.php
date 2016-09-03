<?php
namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class RegisterController extends Controller
{

    public function create(Request $request)
    {
        $data = $request->all();

        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'ace_number' => $data['ace_number'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'project_id' => $data['project_id']
        ]);
    }
}
