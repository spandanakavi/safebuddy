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
            'ace_number' => isset($data['ace_number']) ? $data['ace_number'] : null,
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'project_id' => isset($data['project_id']) ? $data['project_id'] : null,
            'is_parent' => $data['is_parent'],
            'child_email' => isset($data['child_email']) ? $data['child_email'] : null
        ]);
    }
}
